<?php
declare(strict_types=1);

require_once '../../db/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {

    /* -------------------------------------------------
       0) Auth + resolve provider
    ------------------------------------------------- */
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int) $_SESSION['user']['id'];

    $stmtP = $pdo->prepare("SELECT id FROM service_providers WHERE user_id = ? LIMIT 1");
    $stmtP->execute([$userId]);
    $provider = $stmtP->fetch(PDO::FETCH_ASSOC);

    if (!$provider) {
        throw new Exception("Service provider account not found.");
    }

    $providerId = (int) $provider['id'];

    /* -------------------------------------------------
       1) Filters + pagination
    ------------------------------------------------- */
    $search   = trim($_GET['search'] ?? '');
    $category = trim($_GET['category'] ?? '');

    $itemsPerPage = 6;
    $currentPage  = max(1, (int)($_GET['page'] ?? 1));
    $offset       = ($currentPage - 1) * $itemsPerPage;

    /* -------------------------------------------------
       2) Dynamic WHERE
    ------------------------------------------------- */
    $where  = [];
    $params = [];

    // Only available requests
    $where[] = "mr.availability = 'available'";

    // Unassigned OR assigned to me
    $where[] = "(ra.service_provider_id IS NULL OR ra.service_provider_id = ?)";
    $params[] = $providerId;

    if ($category !== '') {
        $where[]  = "LOWER(TRIM(mr.category)) = LOWER(TRIM(?))";
        $params[] = $category;
    }

    if ($search !== '') {
        $where[] = "(mr.request LIKE ? 
                     OR mr.description LIKE ?
                     OR mr.residence LIKE ?
                     OR mr.unit LIKE ?)";
        $like = "%{$search}%";
        $params = array_merge($params, [$like,$like,$like,$like]);
    }

    $whereSql = implode(" AND ", $where);

    /* -------------------------------------------------
       3) Count total items (pagination)
    ------------------------------------------------- */
    $countSql = "
        SELECT COUNT(DISTINCT mr.id)
        FROM maintenance_requests mr
        LEFT JOIN (
            SELECT ra.*,
                   ROW_NUMBER() OVER (
                     PARTITION BY ra.maintenance_request_id
                     ORDER BY ra.created_at DESC
                   ) AS row_num
            FROM maintenance_request_assignments ra
        ) ra
        ON mr.id = ra.maintenance_request_id
        AND ra.row_num = 1
        WHERE {$whereSql}
    ";

    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $totalItems = (int)$countStmt->fetchColumn();

    $totalPages = max(1, ceil($totalItems / $itemsPerPage));

    if ($currentPage > $totalPages) {
        $currentPage = $totalPages;
        $offset = ($currentPage - 1) * $itemsPerPage;
    }

    /* -------------------------------------------------
       4) Fetch filtered requests
    ------------------------------------------------- */
    $fetchSql = "
        SELECT
            mr.id,
            mr.request_date,
            mr.request,
            mr.residence,
            mr.unit,
            mr.category,
            mr.description,
            mr.budget,
            mr.duration,
            mr.created_at
        FROM maintenance_requests mr
        LEFT JOIN (
            SELECT ra.*,
                   ROW_NUMBER() OVER (
                     PARTITION BY ra.maintenance_request_id
                     ORDER BY ra.created_at DESC
                   ) AS row_num
            FROM maintenance_request_assignments ra
        ) ra
        ON mr.id = ra.maintenance_request_id
        AND ra.row_num = 1
        WHERE {$whereSql}
        ORDER BY mr.created_at DESC, mr.id DESC
        LIMIT ? OFFSET ?
    ";

    $stmt = $pdo->prepare($fetchSql);

    $bindParams = array_merge($params, [$itemsPerPage, $offset]);

    $i = 1;
    foreach ($bindParams as $val) {
        $stmt->bindValue($i++, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* -------------------------------------------------
       5) Photos for requests
    ------------------------------------------------- */
    if ($requests) {

        $requestIds = array_column($requests, 'id');
        $placeholders = implode(',', array_fill(0, count($requestIds), '?'));

        $stmtPhotos = $pdo->prepare("
            SELECT maintenance_request_id,id AS photo_id,photo_url
            FROM maintenance_requests_photos
            WHERE maintenance_request_id IN ($placeholders)
        ");
        $stmtPhotos->execute($requestIds);
        $photosRows = $stmtPhotos->fetchAll(PDO::FETCH_ASSOC);

        $photosByRequest = [];
        foreach ($photosRows as $p) {
            $photosByRequest[$p['maintenance_request_id']][] = $p;
        }

        /* -------------------------------------------------
           6) My proposals (exclude withdrawn)
        ------------------------------------------------- */
        $stmtMyProps = $pdo->prepare("
            SELECT *
            FROM maintenance_request_proposals
            WHERE service_provider_id = ?
              AND maintenance_request_id IN ($placeholders)
              AND (status IS NULL OR LOWER(TRIM(status)) <> 'withdrawn')
            ORDER BY id DESC
        ");

        $stmtMyProps->execute(array_merge([$providerId], $requestIds));
        $myProposalRows = $stmtMyProps->fetchAll(PDO::FETCH_ASSOC);

        $myProposalByRequest = [];
        foreach ($myProposalRows as $row) {
            $rid = $row['maintenance_request_id'];
            if (!isset($myProposalByRequest[$rid])) {
                $myProposalByRequest[$rid] = $row;
            }
        }

        /* -------------------------------------------------
           7) Merge extras into requests
        ------------------------------------------------- */
        foreach ($requests as &$r) {
            $rid = $r['id'];

            $r['photos'] = $photosByRequest[$rid] ?? [];
            $r['photo_count'] = count($r['photos']);
            $r['thumbnail'] = $r['photos'][0]['photo_url'] ?? null;

            $mine = $myProposalByRequest[$rid] ?? null;
            $r['has_applied'] = $mine ? true : false;
            $r['my_proposal'] = $mine;
        }
        unset($r);
    }

} catch (Throwable $e) {
    echo "<div class='alert alert-danger'>Error: "
        . htmlspecialchars($e->getMessage())
        . "</div>";
}
