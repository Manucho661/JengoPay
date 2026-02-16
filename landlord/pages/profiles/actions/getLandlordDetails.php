<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../db/connect.php';

try {
    if (empty($_SESSION['user']['id'])) {
        throw new Exception("Not authenticated.");
    }

    $userId = (int)$_SESSION['user']['id'];

    $stmt = $pdo->prepare("
        SELECT
            u.email AS user_email,
            l.*,

            COALESCE(bstats.total_properties, 0) AS total_properties,
            COALESCE(ustats.total_units, 0)      AS total_units,
            COALESCE(tstats.active_tenants, 0)   AS active_tenants

        FROM landlords l
        INNER JOIN users u
            ON u.id = l.user_id

        /* total properties (buildings) */
        LEFT JOIN (
            SELECT landlord_id, COUNT(*) AS total_properties
            FROM buildings
            GROUP BY landlord_id
        ) bstats
            ON bstats.landlord_id = l.id

        /* total units */
        LEFT JOIN (
            SELECT b.landlord_id, COUNT(*) AS total_units
            FROM buildings b
            INNER JOIN building_units bu
                ON bu.building_id = b.id
            GROUP BY b.landlord_id
        ) ustats
            ON ustats.landlord_id = l.id

        /* active tenants (distinct tenant_id with Active tenancy) */
        LEFT JOIN (
            SELECT b.landlord_id, COUNT(DISTINCT tn.tenant_id) AS active_tenants
            FROM buildings b
            INNER JOIN building_units bu
                ON bu.building_id = b.id
            INNER JOIN tenancies tn
                ON tn.unit_id = bu.id
               AND tn.status = 'Active'
            GROUP BY b.landlord_id
        ) tstats
            ON tstats.landlord_id = l.id

        WHERE l.user_id = :user_id
        LIMIT 1
    ");

    $stmt->execute([':user_id' => $userId]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        throw new Exception("Landlord account not found for this user.");
    }

    // Use:
    // $profile['total_properties']
    // $profile['total_units']
    // $profile['active_tenants']

} catch (Throwable $e) {
    $_SESSION['error'] = "Failed to load landlord stats: " . $e->getMessage();
    error_log("Landlord stats error: " . $e->getMessage());
}
