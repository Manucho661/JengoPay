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
       3) Read filters (GET) + pagination
    ------------------------------------------------- */
    $search         = trim((string)($_GET['search'] ?? ''));
    $buildingId     = trim((string)($_GET['building_id'] ?? '')); // optional "single building" filter
    $category       = trim((string)($_GET['category'] ?? ''));
    $ownershipMode  = trim((string)($_GET['ownership_mode'] ?? ''));
    $structure_type = trim((string)($_GET['structure_type'] ?? ''));

    $itemsPerPage = 6;
    $currentPage  = max(1, (int)($_GET['page'] ?? 1));
    $offset       = ($currentPage - 1) * $itemsPerPage;

    /* -------------------------------------------------
       4) Build WHERE dynamically (buildings only)
    ------------------------------------------------- */
    $where  = ["b.landlord_id = ?"];
    $params = [$landlordId];

    if ($buildingId !== '') {
        $where[]  = "b.id = ?";
        $params[] = (int)$buildingId;
    }

    if ($category !== '') {
        $where[]  = "b.category = ?";
        $params[] = $category;
    }

    if ($ownershipMode !== '') {
        $where[]  = "b.ownership_mode = ?";
        $params[] = $ownershipMode;
    }

    if ($structure_type !== '') {
        $where[]  = "b.structure_type = ?";
        $params[] = $structure_type;
    }

    if ($search !== '') {
        $where[]  = "b.building_name LIKE ?";
        $params[] = "%{$search}%";
    }

    $whereSql = implode(" AND ", $where);

    /* -------------------------------------------------
       5) Count total buildings for pagination
    ------------------------------------------------- */
    $countSql = "
        SELECT COUNT(*) AS total_items
        FROM buildings b
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
       6) Fetch filtered + paginated buildings
    ------------------------------------------------- */
    $buildingsSql = "
        SELECT
            b.*
        FROM buildings b
        WHERE {$whereSql}
        ORDER BY b.created_at DESC, b.id DESC
        LIMIT {$itemsPerPage} OFFSET {$offset}
    ";
    $stmtBuildings = $pdo->prepare($buildingsSql);
    $stmtBuildings->execute($params);
    $filteredBuildings = $stmtBuildings->fetchAll(PDO::FETCH_ASSOC);

    /* -------------------------------------------------
       7) Fetch all landlord buildings for the dropdown
          (dropdown should not shrink when filters applied)
    ------------------------------------------------- */
    $stmtAll = $pdo->prepare("
        SELECT id, building_name
        FROM buildings
        WHERE landlord_id = ?
        ORDER BY building_name ASC
    ");
    $stmtAll->execute([$landlordId]);
    $allBuildingsForDropdown = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

    // Now you can use:
    // $filteredBuildings, $allBuildingsForDropdown, $totalPages, $currentPage, etc.

    // units sum
    $landlordId = 5; // example landlord id

    $stmt = $pdo->prepare("
    SELECT
        SUM(CASE WHEN uc.category_name = 'single_unit' THEN 1 ELSE 0 END) AS single_units,
        SUM(CASE WHEN uc.category_name = 'multi_room' THEN 1 ELSE 0 END) AS multi_room_units,
        SUM(CASE WHEN uc.category_name = 'bed_sitter_unit' THEN 1 ELSE 0 END) AS bed_sitter_units
    FROM buildings b
    INNER JOIN building_units bu 
        ON bu.building_id = b.id
    INNER JOIN unit_categories uc 
        ON bu.unit_category_id = uc.id
    WHERE b.landlord_id = :landlord_id
");

    $stmt->execute(['landlord_id' => $landlordId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $singleUnits     = $result['single_units'] ?? 0;
    $multiRoomUnits  = $result['multi_room_units'] ?? 0;
    $bedSitterUnits  = $result['bed_sitter_units'] ?? 0;
    $totalUnits = $singleUnits + $multiRoomUnits + $bedSitterUnits;
} catch (Throwable $e) {
    $errorMessage = "❌ Failed to fetch buildings: " . $e->getMessage();
    echo $errorMessage;
}
