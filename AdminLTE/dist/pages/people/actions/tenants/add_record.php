
    <?php
    include '../../../db/connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Invalid request method');
    }

    $type = $_POST['type'] ?? null;
    if ($type !== 'tenant') {
        http_response_code(400);
        exit('Invalid record type');
    }


    try {
        $pdo->beginTransaction();

        $tenantData = validateTenantData($_POST);
        $uploadedFiles = handleFileUploads($_FILES);

        $userId = insertUser($pdo, $tenantData);
        $tenantId = insertTenant($pdo, $tenantData, $userId);

        if (!empty($tenantData['pets'])) {
            insertPets($pdo, $tenantId, $tenantData['pets']);
        }

        insertIncomeSource($pdo, $tenantId, $_POST);
        insertFiles($pdo, $tenantId, $uploadedFiles);

        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Tenant and user added successfully.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    // Validate form data
    function validateTenantData($data) {
        return [
            'first_name' => $data['tenant_f_name'] ?? '',
            'middle_name' => $data['tenant_m_name'] ?? '',
            'email' => $data['tenant_email'] ?? '',
            'phone' => $data['tenant_m_contact'] ?? '',
            'id_no' => $data['tenant_id_no'] ?? '',
            'building_id' => $data['building_name'] ?? '',
            'unit' => $data['unit_name'] ?? '',
            'income_source' => $data['income_source'] ?? '',
            'work_place' => $data['tenant_workplace'] ?? '',
            'job_title' => $data['tenant_jobtitle'] ?? '',
            'status' => 'active',
            'pets' => json_decode($data['petsData'] ?? '[]', true)
        ];
    }

    // Handle files
    function handleFileUploads($files) {
        $relativePath = 'originaltwo/AdminLTE/dist/pages/people/uploads/';
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $relativePath;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileKeys = [
            'tenant_id_copy' => 'ID COPY',
            'kra_pin_copy' => 'KRA PIN COPY',
            'agreemeny_copy' => 'AGREEMENT COPY'
        ];

        $uploaded = [];

        foreach ($fileKeys as $key => $label) {
            if (!isset($files[$key]) || $files[$key]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Missing or invalid file: $key");
            }

            $originalName = $files[$key]['name'];
            $tempPath = $files[$key]['tmp_name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

            if (!in_array($extension, $allowed)) {
                throw new Exception("Invalid file type for $key");
            }

            $uniqueName = uniqid(pathinfo($originalName, PATHINFO_FILENAME) . '_') . '.' . $extension;
            $destination = $uploadDir . $uniqueName;
            $browserPath = '/' . $relativePath . $uniqueName;

            if (!move_uploaded_file($tempPath, $destination)) {
                throw new Exception("Failed to move $key");
            }

            $uploaded[] = [
                'name' => $label,
                'path' => $browserPath
            ];
        }

        return $uploaded;
    }

    // Insert user
    function insertUser($pdo, $data) {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, middle_name, email) VALUES (?, ?, ?)");
        $stmt->execute([$data['first_name'], $data['middle_name'], $data['email']]);
        return $pdo->lastInsertId();
    }

    // Insert Tenant
    function insertTenant($pdo, $data, $userId) {
        $stmt = $pdo->prepare("INSERT INTO tenants (user_id, phone_number, id_no, building_id, unit, income_source, work_place, job_title, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $data['phone'],
            $data['id_no'],
            $data['building_id'],
            $data['unit'],
            $data['income_source'],
            $data['work_place'],
            $data['job_title'],
            $data['status']
        ]);
        return $pdo->lastInsertId();
    }


    // insert into pets
    function insertPets($pdo, $tenantId, $pets) {
        $stmt = $pdo->prepare("INSERT INTO pets (tenant_id, type, weight, license) VALUES (:tenant_id, :type, :weight, :license)");
        foreach ($pets as $pet) {
            $stmt->execute([
                ':tenant_id' => $tenantId,
                ':type' => $pet['type'] ?? '',
                ':weight' => $pet['weight'] ?? '',
                ':license' => $pet['license'] ?? ''
            ]);
        }
    }


    // insert into files
    function insertFiles($pdo, $tenantId, $files) {
        $stmt = $pdo->prepare("INSERT INTO files (tenant_id, file_name, file_path) VALUES (?, ?, ?)");
        foreach ($files as $file) {
            $stmt->execute([$tenantId, $file['name'], $file['path']]);
        }
    }


    // Insert income source
    function insertIncomeSource($pdo, $tenantId, $data) {
        $stmt = $pdo->prepare("INSERT INTO income_source (tenant_id, income_type, job_title, employer_name, employer_contact, place_of_work, business_type, kra_pin, business_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $tenantId,
            $data['income_source'] ?? null,
            $data['job_title'] ?? null,
            $data['employer_name'] ?? null,
            $data['employer_contact'] ?? null,
            $data['tenant_workplace'] ?? null,
            $data['business_type'] ?? null,
            $data['kra_pin'] ?? null,
            $data['business_location'] ?? null
        ]);
    }

