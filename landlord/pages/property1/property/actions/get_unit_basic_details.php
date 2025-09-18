<?php
// get_unit_basic_details.php
include '../db/connect.php'; // your PDO connection file

if (isset($_GET['unit_id'])) {
    $unit_id = intval($_GET['unit_id']);

    try {
        $stmt = $pdo->prepare("SELECT unit_number, rooms, unit_type, room_type, floor_number FROM units WHERE unit_id = :unit_id");
        $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
        $stmt->execute();

        $unit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($unit) {
            echo json_encode(['success' => true, 'data' => $unit]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unit not found']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
