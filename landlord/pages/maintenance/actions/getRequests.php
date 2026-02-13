<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

try {
    // 1) Auth + landlord_id
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int) $_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
    $stmt->execute([$userId]);
    $landlord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$landlord) {
        throw new Exception("Landlord account not found for this user.");
    }

    $landlord_id = (int) $landlord['id'];

    /* -------------------------------------------------
       2) Read filters (GET) + pagination
    ------------------------------------------------- */
    $search     = trim($_GET['search'] ?? '');
    $buildingId = trim($_GET['building_id'] ?? '');
    $statusFil  = trim($_GET['status'] ?? '');
    $dateFrom   = trim($_GET['date_from'] ?? '');
    $dateTo     = trim($_GET['date_to'] ?? '');

    $itemsPerPage = 6;
    $currentPage  = max(1, (int)($_GET['page'] ?? 1));
    $offset       = ($currentPage - 1) * $itemsPerPage;

    /* -------------------------------------------------
       3) Build WHERE dynamically (filters only if set)
    ------------------------------------------------- */
    $where  = ["mr.landlord_id = ?"];
    $params = [$landlord_id];

    if ($buildingId !== '') {
        $where[]  = "mr.building_id = ?";
        $params[] = (int) $buildingId;
    }

    if ($statusFil !== '') {
        $where[]  = "mr.status = ?";
        $params[] = $statusFil;
    }

    if ($dateFrom !== '') {
        $where[]  = "DATE(mr.created_at) >= ?";
        $params[] = $dateFrom; // expects YYYY-MM-DD
    }

    if ($dateTo !== '') {
        $where[]  = "DATE(mr.created_at) <= ?";
        $params[] = $dateTo; // expects YYYY-MM-DD
    }

    if ($search !== '') {
        // search on title/description/provider name (provider is from LEFT JOIN below)
        $where[] = "(mr.title LIKE ? OR mr.description LIKE ? OR p.name LIKE ?)";
        $like = "%{$search}%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $whereSql = implode(" AND ", $where);

    /* -------------------------------------------------
       4) Count total items (same WHERE)
    ------------------------------------------------- */
    $countSql = "
        SELECT COUNT(DISTINCT mr.id) AS total_items
        FROM maintenance_requests mr
        LEFT JOIN (
            SELECT
                ra.*,
                ROW_NUMBER() OVER (
                    PARTITION BY ra.maintenance_request_id
                    ORDER BY ra.created_at DESC
                ) AS row_num
            FROM maintenance_request_assignments ra
        ) ra
            ON mr.id = ra.maintenance_request_id
           AND ra.row_num = 1
        LEFT JOIN service_providers p
            ON ra.service_provider_id = p.id
        WHERE {$whereSql}
    ";

    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalItems = (int)($countStmt->fetchColumn() ?? 0);
    $totalPages = (int)ceil($totalItems / $itemsPerPage);

    if ($totalPages > 0 && $currentPage > $totalPages) {
        $currentPage = $totalPages;
        $offset = ($currentPage - 1) * $itemsPerPage;
    }

    /* -------------------------------------------------
       5) Fetch paginated results (same WHERE)
    ------------------------------------------------- */
    $sql = "
        SELECT
            mr.*,
            b.building_name,
            bu.unit_number,
            ra.id AS assignment_id,
            p.name  AS provider_name,
            p.email AS provider_email,
            p.phone AS provider_phone
        FROM maintenance_requests mr
        LEFT JOIN buildings b
            ON mr.building_id = b.id
        LEFT JOIN building_units bu
            ON mr.building_unit_id = bu.id
        LEFT JOIN (
            SELECT
                ra.*,
                ROW_NUMBER() OVER (
                    PARTITION BY ra.maintenance_request_id
                    ORDER BY ra.created_at DESC
                ) AS row_num
            FROM maintenance_request_assignments ra
        ) ra
            ON mr.id = ra.maintenance_request_id
           AND ra.row_num = 1
        LEFT JOIN service_providers p
            ON ra.service_provider_id = p.id
        WHERE {$whereSql}
        ORDER BY mr.created_at DESC
        LIMIT {$itemsPerPage} OFFSET {$offset}
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* -------------------------------------------------
       6) Counters (for just the page)
       If you want counters for ALL filtered items, do a separate GROUP BY query.
    ------------------------------------------------- */
    $totalRequests = count($requests);
    $openRequests = 0;
    $completedRequests = 0;
    $closedRequests = 0;

    foreach ($requests as $request) {
        $st = strtolower(trim($request['status'] ?? ''));
        if ($st === 'open') $openRequests++;
        elseif ($st === 'completed') $completedRequests++;
        elseif ($st === 'closed') $closedRequests++;
    }


    // --- 1) Determine current year (server time) ---
    $currentYear = (int) date('Y'); // e.g. 2026

    // --- 2) Query counts per month for ONLY current year (using your same filters) ---
    // IMPORTANT: keep the same $whereSql + $params you already built for filtering,
    // but we add a year condition for the graph.
    $graphWhere = $whereSql . " AND YEAR(mr.created_at) = ?";
    $graphParams = array_merge($params, [$currentYear]);

    $graphSql = "
  SELECT
    DATE_FORMAT(mr.created_at, '%Y-%m') AS month_key,
    COUNT(*) AS total
  FROM maintenance_requests mr
  WHERE {$graphWhere}
  GROUP BY month_key
  ORDER BY month_key ASC
";

    $gStmt = $pdo->prepare($graphSql);
    $gStmt->execute($graphParams);
    $graphRows = $gStmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 3) Map results: ['2026-02' => 5, ...] ---
    $monthMap = [];
    foreach ($graphRows as $r) {
        $monthMap[$r['month_key']] = (int)$r['total'];
    }

    // --- 4) Build Jan..Dec for current year, fill missing with 0 ---
    $monthLabels = [];
    $monthTotals = [];

    for ($m = 1; $m <= 12; $m++) {
        $key = sprintf('%d-%02d', $currentYear, $m); // 2026-01 ... 2026-12
        $monthLabels[] = $key;
        $monthTotals[] = $monthMap[$key] ?? 0;
    }

    $monthLabelsJson = json_encode($monthLabels);
    $monthTotalsJson = json_encode($monthTotals);
} catch (Throwable $e) {
    $requests = [];
    $requestsError = $e->getMessage();

    $totalRequests = 0;
    $openRequests = 0;
    $completedRequests = 0;
    $closedRequests = 0;
}
