<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../db/connect.php'; // make sure $pdo exists

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
   Filters (GET)
------------------------------------------------- */
    $search     = trim((string)($_GET['search'] ?? ''));
    $buildingId = trim((string)($_GET['building_id'] ?? ''));
    $category   = trim((string)($_GET['category'] ?? '')); // single_unit | multi_room | bed_sitter_unit
    $purpose    = trim((string)($_GET['purpose'] ?? ''));  // if you have it on units
    $occStatus  = trim((string)($_GET['occupancy_status'] ?? '')); // Occupied | Vacant (optional)

    /* -------------------------------------------------
   Pagination
------------------------------------------------- */
    $itemsPerPage = 5;
    $currentPage  = max(1, (int)($_GET['page'] ?? 1));
    $offset       = ($currentPage - 1) * $itemsPerPage;

    /* -------------------------------------------------
   WHERE (units scoped by landlord via buildings)
------------------------------------------------- */
    $where  = ["b.landlord_id = ?"];
    $params = [$landlordId];

    if ($buildingId !== '') {
        $where[]  = "b.id = ?";
        $params[] = (int)$buildingId;
    }

    if ($category !== '') {
        $where[]  = "uc.category_name = ?";
        $params[] = $category;
    }

    /**
     * If purpose is a column in building_units (example: ownership_mode),
     * adjust the column name to match your schema.
     */
    if ($purpose !== '') {
        $where[]  = "bu.ownership_mode = ?";
        $params[] = $purpose;
    }

    if ($search !== '') {
        $where[]  = "bu.unit_number LIKE ?";
        $params[] = "%{$search}%";
    }

    /**
     * Occupancy filter depends on active tenancy existence
     */
    if ($occStatus !== '') {
        if (strtolower($occStatus) === 'occupied') {
            $where[] = "at.tenant_id IS NOT NULL";
        } elseif (strtolower($occStatus) === 'vacant') {
            $where[] = "at.tenant_id IS NULL";
        }
    }

    $whereSql = implode(" AND ", $where);

    /* -------------------------------------------------
   Count total units (for pagination)
------------------------------------------------- */
    $countSql = "
    SELECT COUNT(*) AS total_items
    FROM building_units bu
    INNER JOIN buildings b ON b.id = bu.building_id
    INNER JOIN unit_categories uc ON uc.id = bu.unit_category_id
    LEFT JOIN (
        SELECT t1.unit_id, t1.tenant_id
        FROM tenancies t1
        INNER JOIN (
            SELECT unit_id, MAX(id) AS max_id
            FROM tenancies
            WHERE status = 'Active'
            GROUP BY unit_id
        ) x ON x.unit_id = t1.unit_id AND x.max_id = t1.id
    ) at ON at.unit_id = bu.id
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
   Fetch paginated units + active tenant first name
------------------------------------------------- */
    $unitsSql = "
    SELECT
        bu.*,
        b.building_name,
        uc.category_name,

        at.tenant_id,
        te.first_name AS tenant_first_name

    FROM building_units bu
    INNER JOIN buildings b ON b.id = bu.building_id
    INNER JOIN unit_categories uc ON uc.id = bu.unit_category_id

    LEFT JOIN (
        SELECT t1.unit_id, t1.tenant_id
        FROM tenancies t1
        INNER JOIN (
            SELECT unit_id, MAX(id) AS max_id
            FROM tenancies
            WHERE status = 'Active'
            GROUP BY unit_id
        ) x ON x.unit_id = t1.unit_id AND x.max_id = t1.id
    ) at ON at.unit_id = bu.id

    LEFT JOIN tenants te ON te.id = at.tenant_id

    WHERE {$whereSql}
    ORDER BY b.created_at DESC, bu.id DESC
    LIMIT {$itemsPerPage} OFFSET {$offset}
";

    $stmtUnits = $pdo->prepare($unitsSql);
    $stmtUnits->execute($params);
    $units = $stmtUnits->fetchAll(PDO::FETCH_ASSOC);

    $occSql = "
    SELECT
        SUM(CASE WHEN at.tenant_id IS NOT NULL THEN 1 ELSE 0 END) AS total_occupied,
        SUM(CASE WHEN at.tenant_id IS NULL THEN 1 ELSE 0 END) AS total_vacant
    FROM building_units bu
    INNER JOIN buildings b ON b.id = bu.building_id
    INNER JOIN unit_categories uc ON uc.id = bu.unit_category_id
    LEFT JOIN (
        SELECT t1.unit_id, t1.tenant_id
        FROM tenancies t1
        INNER JOIN (
            SELECT unit_id, MAX(id) AS max_id
            FROM tenancies
            WHERE status = 'Active'
            GROUP BY unit_id
        ) x ON x.unit_id = t1.unit_id AND x.max_id = t1.id
    ) at ON at.unit_id = bu.id
    WHERE {$whereSql}
";

$occStmt = $pdo->prepare($occSql);
$occStmt->execute($params);
$occ = $occStmt->fetch(PDO::FETCH_ASSOC);

$totalOccupied = (int)($occ['total_occupied'] ?? 0);
$totalVacant   = (int)($occ['total_vacant'] ?? 0);


} catch (Throwable $e) {
    $errorMessage = "❌ Failed to fetch buildings: " . $e->getMessage();
    echo $errorMessage;
    
}
