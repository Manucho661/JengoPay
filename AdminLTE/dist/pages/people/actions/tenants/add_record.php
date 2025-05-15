
<?php
 include '../../../db/connect.php';

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
                $tenant_id_copy = $_FILES['tenant_id_copy']?? '';
                $work_place = $_POST['tenant_workplace'] ?? '';
                $job_title = $_POST['tenant_jobtitle'] ?? '';
                $status = 'active';



                if ($first_name && $middle_name && $pets && $email && $phone &&
                    $id_no && $residence && $unit && $income_source && $work_place && $job_title &&
                    isset($_FILES['tenant_id_copy']) &&
                    $_FILES['tenant_id_copy']['error'] === UPLOAD_ERR_OK
                    ) {
                       // ✅ Step 1: Prepare file upload
                       // Step 1: Set target folder (relative to current script)
                            $uploadDir = __DIR__ . '/files/'; // Absolute path ensures it works correctly

                            // Step 2: Create folder if it doesn't exist
                            if (!file_exists($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }

                            // Step 3: Get file details
                            $originalName = $_FILES['tenant_id_copy']['name'];
                            $tempPath = $_FILES['tenant_id_copy']['tmp_name'];
                            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                            // Step 4: Validate file type
                            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                            if (!in_array($extension, $allowed)) {
                                echo "Invalid file type. Only PDF, JPG, JPEG, PNG allowed.";
                                exit;
                            }

                            // Step 5: Set destination file path
                            $filename = uniqid('id_copy_') . '.' . $extension;
                            $destination = $uploadDir . $filename;

                            // Step 6: Move uploaded file
                            if (!move_uploaded_file($tempPath, $destination)) {
                                echo "Failed to move uploaded file.";
                                exit;
                            }

                            // ✅ Optional: Save filename to DB later
                            echo "File uploaded successfully as $filename";


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

                        // // Step 4: Insert into files
                         $stmtTenant = $pdo->prepare("INSERT INTO files (tenant_id, file_path) VALUES (?, ?)");
                         $stmtTenant->execute([$tenant_id, $filename ]);





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
