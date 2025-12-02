                                                                                                                              <?php
header('Content-Type: application/json');

require_once '../../db/connect.php';

try {

    // Get page number and limit from query parameters
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            // Calculate offset
            $offset = ($page - 1) * $limit;

            // Get total number of records
            $countQuery = $pdo->prepare("
            SELECT COUNT(*) AS total
            FROM maintenance_requests mr
            LEFT JOIN (
                SELECT 
                    ra.*, 
                    ROW_NUMBER() OVER (PARTITION BY ra.request_id ORDER BY ra.created_at DESC) AS row_num
                FROM request_assignments ra
            ) ra ON mr.id = ra.request_id AND ra.row_num = 1
            LEFT JOIN maintenance_photos mp ON mp.maintenance_request_id = mr.id
            LEFT JOIN providers p ON ra.provider_id = p.id
        ");

        $countQuery->execute();
        $totalRecords = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];
        // Calculate total pages
        $totalPages = ceil($totalRecords / $limit);


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
            LEFT JOIN maintenance_photos mp ON mp.maintenance_request_id = mr.id
            LEFT JOIN providers p ON ra.provider_id = p.id
            LIMIT ? OFFSET ?
        ");
    $stmt->execute([$limit, $offset]);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Calculate start and end record numbers for display
    $start = $totalRecords > 0 ? $offset + 1 : 0;
    $end = min($offset + $limit, $totalRecords);

    echo json_encode([
        'success' => true,
        'data' => $requests,
        'totalRecords' => (int)$totalRecords,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'per_page' => $limit,
        'start' => $start,
        'end' => $end
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
