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
       3) Fetch only tenants in buildings owned by landlord
          (keep existing condition)
    ------------------------------------------------- */
    $sql = "
        SELECT
            t.*,
            tn.move_in_date,
            tn.created_at AS tenancy_created_at,
            bu.unit_number,
            b.building_name,
            bu.unit_category_id
        FROM tenants t
        INNER JOIN tenancies tn
            ON tn.tenant_id = t.id
        INNER JOIN building_units bu
            ON bu.id = tn.unit_id
        INNER JOIN buildings b
            ON b.id = bu.building_id
        WHERE tn.status = 'Active'
          AND b.landlord_id = :landlord_id
        ORDER BY t.created_at ASC, t.tenant_reg DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':landlord_id' => $landlordId]);

    $allTenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tenantCount = count($allTenants);

    /* -------------------------------------------------
       4) Totals per unit category (single_unit, bed_sitter_unit, multi_room)
    ------------------------------------------------- */
    $countSql = "
        SELECT
            uc.category_name,
            COUNT(*) AS total
        FROM tenancies tn
        INNER JOIN building_units bu
            ON bu.id = tn.unit_id
        INNER JOIN buildings b
            ON b.id = bu.building_id
        INNER JOIN unit_categories uc
            ON uc.id = bu.unit_category_id
        WHERE tn.status = 'Active'
          AND b.landlord_id = :landlord_id
          AND uc.category_name IN ('single_unit', 'bed_sitter_unit', 'multi_room')
        GROUP BY uc.category_name
    ";

    $stmt2 = $pdo->prepare($countSql);
    $stmt2->execute([':landlord_id' => $landlordId]);
    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Default to 0 in case a category has no tenants
    $singleUnitTenants   = 0;
    $bedSitterTenants    = 0;
    $multiRoomTenants    = 0;

    foreach ($rows as $r) {
        $name = strtolower(trim((string)($r['category_name'] ?? '')));
        $total = (int)($r['total'] ?? 0);

        if ($name === 'single_unit') {
            $singleUnitTenants = $total;
        } elseif ($name === 'bed_sitter_unit') {
            $bedSitterTenants = $total;
        } elseif ($name === 'multi_room') {
            $multiRoomTenants = $total;
        }
    }

} catch (Throwable $e) {
    error_log("Fetch tenants error: " . $e->getMessage());
    $errorMessage = "❌ Failed to fetch tenants: " . $e->getMessage();
    echo $errorMessage;
}
