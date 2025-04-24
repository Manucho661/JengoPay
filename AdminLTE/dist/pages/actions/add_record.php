
<?php
 include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    switch ($type) {
        case 'provider':
            $name = $_POST['provider_name'] ?? '';
            $service = $_POST['service_type'] ?? '';

            if (!empty($name) && !empty($service)) {
                $stmt = $conn->prepare("INSERT INTO providers (provider_name, service_type) VALUES (?, ?)");
                if ($stmt->execute([$name, $service])) {
                    echo "Provider added successfully!";
                } else {
                    echo "Failed to add provider.";
                }
            } else {
                echo "All provider fields are required.";
            }
            break;

        case 'tenant':
            case 'tenant':
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $id_no = $_POST['id'] ?? '';
                $residence = $_POST['residence'] ?? '';
                $unit = $_POST['unit'] ?? '';
                $status = 'active';
    
                if ($name && $email && $phone && $id_no && $residence && $unit) {
                    try {
                        $conn->beginTransaction();
    
                        // Step 1: Insert into users
                        $stmtUser = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
                        $stmtUser->execute([$name, $email]);
                        $user_id = $conn->lastInsertId();
    
                        // Step 2: Insert into tenants
                        $stmtTenant = $conn->prepare("INSERT INTO tenants (user_id, phone_number, id_no, residence, unit, status) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmtTenant->execute([$user_id, $phone, $id_no, $residence, $unit, $status]);
    
                        $conn->commit();
                        echo "Tenant and user added successfully!";
                    } catch (Exception $e) {
                        $conn->rollBack();
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "All tenant fields are required.";
                }
    
            break;

        // 🔜 You can add more like this:
        // case 'maintenance':
        //     ...
        //     break;

        default:
            echo "Invalid record type.";
            break;
    }
} else {
    echo "Invalid request.";
}
