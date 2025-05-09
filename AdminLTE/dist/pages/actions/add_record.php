
<?php
 include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    switch ($type) {
        case 'provider':
            $name = $_POST['provider_name'] ?? '';
            $service = $_POST['service_type'] ?? '';

            if (!empty($name) && !empty($service)) {
                $stmt = $pdo->prepare("INSERT INTO providers (provider_name, service_type) VALUES (?, ?)");
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
                $first_name = $_POST['tenant_f_name'] ?? '';
                $middle_name = $_POST['tenant_m_name'] ?? '';
                $email = $_POST['tenant_email'] ?? '';
                $phone = $_POST['tenant_m_contact'] ?? '';
                $id_no = $_POST['tenant_id_no'] ?? '';
                $residence = $_POST['building_name'] ?? '';
                $pets = $_POST['pets']?? '';
                $unit = $_POST['unit_name'] ?? '';
                $income_source = $_POST['income_source'] ?? '';
                
                $work_place = $_POST['tenant_workplace'] ?? '';
                $job_title = $_POST['tenant_jobtitle'] ?? '';
                $status = 'active';

                if ($first_name && $middle_name && $pets && $email && $phone && $id_no && $residence && $unit && $income_source && $work_place && $job_title) {
                    try {
                        $pdo->beginTransaction();

                        // Step 1: Insert into users
                        $stmtUser = $pdo->prepare("INSERT INTO users (first_name, middle_name, email) VALUES (?,?, ?)");
                        $stmtUser->execute([$first_name, $middle_name,  $email]);
                        $user_id = $pdo->lastInsertId();

                        // Step 2: Insert into tenants
                        $stmtTenant = $pdo->prepare("INSERT INTO tenants (user_id, phone_number, id_no, residence, unit,income_source,work_place, job_title, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtTenant->execute([$user_id, $phone, $id_no, $residence, $unit, $income_source,$work_place, $job_title, $status]);
                        $tenant_id = $pdo->lastInsertId();


                        //step 3: Insert into pets
                        foreach ($pets as $pet) {
                        
                            $stmtPet = $pdo->prepare("INSERT INTO pets (tenant_id, pet_name) VALUES (?, ?)");
                            $stmtPet->execute([$tenant_id, $pet]);
                        }
                

                        $pdo->commit();
                        echo "Tenant and user added successfully!";
                    } catch (Exception $e) {
                        $pdo->rollBack();
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
