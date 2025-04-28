<?php
 include '../db/connect.php';


 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;

    if (!$id || !$type) {
        echo "Missing parameters.";
        exit;
    }

    try {
        switch ($type) {
            case 'users':
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
                break;
            case 'property':
                $stmt = $pdo->prepare("DELETE FROM properties WHERE property_id = :id");
                break;
            case 'maintenance':
                $stmt = $pdo->prepare("DELETE FROM maintenance_requests WHERE request_id = :id");
                break;
            default:
                echo "Invalid type.";
                exit;
        }

        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo "Record deleted successfully.";
        } else {
            echo "No record found or already deleted.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

