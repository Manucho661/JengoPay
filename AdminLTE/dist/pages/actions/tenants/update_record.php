<?php
 include '../../db/connect.php';

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;



    if (!$id || !$type) {
        echo "Missing parameters.";
        exit;
    }

    try {
        switch ($type) {
            case 'activate':
                $stmt = $pdo->prepare("UPDATE tenants SET status = 'active' WHERE user_id = :id");
                break;
            case 'deactivate':
                $stmt = $pdo->prepare("UPDATE tenants SET status = 'inactive' WHERE user_id = :id");
                break;  
            default:
                echo "Invalid type.";
                exit;
        }

        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo "Record updated successfully.";
        } else {
            echo "No record found or already deleted.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

