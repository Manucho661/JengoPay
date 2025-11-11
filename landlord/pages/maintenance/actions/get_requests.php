                                                                                                                              <?php
header('Content-Type: application/json');

require_once '../../db/connect.php';

try {
    // Fetch maintenance requests along with provider details
    $stmt = $pdo->prepare("
    SELECT
        mr.*,
        ra.id AS assignment_id,
        ra.status,
        p.name AS provider_name,
        p.email AS provider_email,
        p.phone AS provider_phone,
        mp.photo_url
    FROM
        maintenance_requests mr
    LEFT JOIN (
        SELECT 
            ra.*, 
            ROW_NUMBER() OVER (PARTITION BY ra.request_id ORDER BY ra.created_at DESC) AS row_num
        FROM
            request_assignments ra
    ) ra ON mr.id = ra.request_id AND ra.row_num = 1
    LEFT JOIN
        maintenance_photos mp ON mp.maintenance_request_id = mr.id
    LEFT JOIN 
        providers p ON ra.provider_id = p.id
");



    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $requests
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
