<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$expenses = [];
$monthlyTotals = [];
$errorMessage = null;

try {
    /* -------------------------------------------------
       1) Auth check
    ------------------------------------------------- */
    if (!isset($_SESSION['user']['id'])) {
        throw new Exception('User not authenticated.');
    }

    $userId = (int) $_SESSION['user']['id'];

    /* -------------------------------------------------
       2) Resolve landlord_id for this user
    ------------------------------------------------- */
    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception('Landlord record not found.');
    }

    $landlordId = (int) $landlord['id'];

    /* -------------------------------------------------
       2.1) Read filters (GET) + pagination
    ------------------------------------------------- */
    $search     = trim($_GET['search'] ?? '');
    $buildingId = $_GET['building_id'] ?? '';
    $statusFil     = $_GET['status'] ?? '';
    $dateFrom   = $_GET['date_from'] ?? '';
    $dateTo     = $_GET['date_to'] ?? '';

    $hasFilters = ($search !== '' || $buildingId !== '' || $statusFil !== '' || $dateFrom !== '' || $dateTo !== '');


    $itemsPerPage = 6;
    $currentPage  = max(1, (int)($_GET['page'] ?? 1));
    $offset       = ($currentPage - 1) * $itemsPerPage;

    /* -------------------------------------------------
       3) Build WHERE dynamically (filters applied once)
    ------------------------------------------------- */
    $where  = ["e.landlord_id = ?"];
    $params = [$landlordId];

    if ($buildingId !== '') {
        $where[]  = "e.building_id = ?";
        $params[] = (int)$buildingId;
    }

    if ($statusFil !== '') {
        $where[]  = "e.status = ?";
        $params[] = $statusFil;
    }

    // dates
    if ($dateFrom !== '') {
        $where[]  = "DATE(e.expense_date) >= ?";
        $params[] = $dateFrom;
    }

    if ($dateTo !== '') {
        $where[]  = "DATE(e.expense_date) <= ?";
        $params[] = $dateTo;
    }

    // search
    if ($search !== '') {
        $where[] = "(s.supplier_name LIKE ? OR e.expense_no LIKE ?)";
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }


    $whereSql = implode(" AND ", $where);

    /* -------------------------------------------------
       3.1) Count total items FOR PAGINATION (with same filters)
    ------------------------------------------------- */
    $countSql = "
        SELECT COUNT(DISTINCT e.id) AS total_items
        FROM expenses e
        LEFT JOIN suppliers s ON e.supplier_id = s.id
        WHERE {$whereSql}
    ";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalItems = (int)($countStmt->fetchColumn() ?? 0);
    $totalPages = (int)ceil($totalItems / $itemsPerPage);

    // optional: if user is on a page beyond last page after filtering, snap back
    if ($totalPages > 0 && $currentPage > $totalPages) {
        $currentPage = $totalPages;
        $offset = ($currentPage - 1) * $itemsPerPage;
    }

    /* -------------------------------------------------
       3.2) Fetch expenses (filtered + paginated) with total_paid per expense
    ------------------------------------------------- */
    $listSql = "
        SELECT 
            e.*,
            s.supplier_name,
            COALESCE(SUM(ep.amount_paid), 0) AS total_paid
        FROM expenses e
        LEFT JOIN expense_payments ep ON e.id = ep.expense_id
        LEFT JOIN suppliers s ON e.supplier_id = s.id
        WHERE {$whereSql}
        GROUP BY e.id
        ORDER BY e.created_at DESC
        LIMIT {$itemsPerPage} OFFSET {$offset}
    ";

    $listStmt = $pdo->prepare($listSql);
    $listStmt->execute($params);
    $expenses = $listStmt->fetchAll(PDO::FETCH_ASSOC);

    /* -------------------------------------------------
       4) Summary values (NOTE: this is for CURRENT PAGE results only)
       If you want landlord-wide totals, keep your existing queries below.
    ------------------------------------------------- */
    $expenseItemsNumber = $totalItems; // better: total items under filter (not just current page)

    // Total paid across the CURRENT PAGE results (optional)
    $totalAmount = 0.0;
    foreach ($expenses as $exp) {
        $totalAmount += (float)($exp['total_paid'] ?? 0);
    }

    /* -------------------------------------------------
       5) Totals by status (landlord-wide, unchanged)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT
            SUM(CASE WHEN status = 'paid' THEN total ELSE 0 END) AS total_paid,
            SUM(CASE WHEN status = 'unpaid' THEN total ELSE 0 END) AS total_unpaid,
            SUM(CASE WHEN status = 'overpaid' THEN total ELSE 0 END) AS total_overpaid,
            SUM(CASE WHEN status = 'partially paid' THEN total ELSE 0 END) AS partially_paid
        FROM expenses
        WHERE landlord_id = ?
    ");
    $stmt->execute([$landlordId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

    $fullyPaidExact       = (float)($row['total_paid'] ?? 0);
    $fullyPaidOver        = (float)($row['total_overpaid'] ?? 0);
    $unpaidTotal          = (float)($row['total_unpaid'] ?? 0);
    $partiallyPaidTotal   = (float)($row['partially_paid'] ?? 0);

    /* -------------------------------------------------
       6) Total amount paid (landlord-wide)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(ep.amount_paid), 0) AS total_paid
        FROM expense_payments ep
        INNER JOIN expenses e ON e.id = ep.expense_id
        WHERE e.landlord_id = ?
    ");
    $stmt->execute([$landlordId]);
    $totalAmountPaid = (float)(($stmt->fetch(PDO::FETCH_ASSOC)['total_paid']) ?? 0);

    /* -------------------------------------------------
       7) Excess paid for overpaid expenses (landlord-wide)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(GREATEST(paid_sum - total, 0)), 0) AS excess_amount
        FROM (
            SELECT e.id, e.total, COALESCE(SUM(ep.amount_paid), 0) AS paid_sum
            FROM expenses e
            LEFT JOIN expense_payments ep ON ep.expense_id = e.id
            WHERE e.landlord_id = ?
              AND e.status = 'overpaid'
            GROUP BY e.id
        ) t
    ");
    $stmt->execute([$landlordId]);
    $excess_amount = (float)(($stmt->fetch(PDO::FETCH_ASSOC)['excess_amount']) ?? 0);

    $totalAmountSend = $totalAmountPaid + $excess_amount;

    /* -------------------------------------------------
       8) Pending total for partially paid (landlord-wide)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(GREATEST(total - paid_sum, 0)), 0) AS pending_total
        FROM (
            SELECT e.id, e.total, COALESCE(SUM(ep.amount_paid), 0) AS paid_sum
            FROM expenses e
            LEFT JOIN expense_payments ep ON ep.expense_id = e.id
            WHERE e.landlord_id = ?
              AND e.status = 'partially paid'
            GROUP BY e.id
        ) t
    ");
    $stmt->execute([$landlordId]);
    $pendingTotal = (float)(($stmt->fetch(PDO::FETCH_ASSOC)['pending_total']) ?? 0);

    /* -------------------------------------------------
       9) Total remaining (landlord-wide)
    ------------------------------------------------- */
    $TotalRemaining = $unpaidTotal + $pendingTotal;

    /* -------------------------------------------------
       10) Monthly totals (landlord-wide)
    ------------------------------------------------- */
    $stmt = $pdo->prepare("
        SELECT
            MONTH(expense_date) AS month,
            SUM(total) AS total
        FROM expenses
        WHERE landlord_id = ?
        GROUP BY MONTH(expense_date)
    ");
    $stmt->execute([$landlordId]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthlyTotals[(int)$row['month']] = (float)$row['total'];
    }
} catch (Throwable $e) {
    $errorMessage = "❌ Failed to fetch expenses: " . $e->getMessage();
    echo  $errorMessage;
}
