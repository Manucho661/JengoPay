
<?php
 include '../../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
                $inspection_number = $_POST['inspection_number'] ?? '';
                $building_name = $_POST['building_name'] ?? '';
                $unit_name = $_POST['unit_name'] ?? '';
                $inspection_type = $_POST['inspection_type'] ?? '';
                $date = $_POST['date'] ?? '';
                

                if ($inspection_number && $building_name && $unit_name && $inspection_type && $date) {
                    try {
                        $pdo->beginTransaction();

                        // Step 1: Insert into users
                        $stmtUser = $pdo->prepare("INSERT INTO inspections (inspection_number, building_name , inspection_type, date) VALUES (?,?, ?, ?)");
                        $stmtUser->execute([$inspection_number, $building_name,  $unit_name, $date]);
                        $user_id = $pdo->lastInsertId();
                        $pdo->commit();
                        echo "New Schedule added successfully!";
                    } catch (Exception $e) {
                        $pdo->rollBack();
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "All New Inspection fields are required.";
                }
       
    }
 else {
    echo "Invalid request.";
}
