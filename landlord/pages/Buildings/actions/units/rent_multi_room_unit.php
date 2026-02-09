<?php
if (isset($_POST['rent_unit'])) {

  $tm = md5(time()); // Unique prefix for uploaded files

  // --------------------------------------------
  // FILE UPLOAD HANDLER
  // --------------------------------------------
  function uploadFile($fileKey, $tm)
  {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
      return null;
    }

    $name = basename($_FILES[$fileKey]['name']);
    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);

    $fileName = $tm . "_" . $safeName;

    // DISK path (for PHP)
    $diskPath = $_SERVER['DOCUMENT_ROOT'] . "/jengopay/landlord/pages/buildings/uploads/" . $fileName;

    // WEB path (for browser / DB)
    $webPath = "/jengopay/landlord/pages/buildings/uploads/" . $fileName;

    // Ensure folder exists
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/jengopay/landlord/pages/buildings/uploads/";
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    move_uploaded_file($_FILES[$fileKey]['tmp_name'], $diskPath);

    return $webPath;
  }


  $files = [
    'id_upload' => uploadFile('id_upload', $tm),
    'tax_pin' => uploadFile('tax_pin_copy', $tm),
    'rental_agreement' => uploadFile('rental_agreement', $tm)
  ];

  // --------------------------------------------
  // COLLECT FORM DATA
  // --------------------------------------------
  $tenantData = [
    'first_name'   => $_POST['tfirst_name'] ?? null,
    'middle_name'  => $_POST['tmiddle_name'] ?? null,
    'last_name'    => $_POST['tlast_name'] ?? null,
    'phone'        => $_POST['tmain_contact'] ?? null,
    'alt_phone'    => $_POST['talt_contact'] ?? null,
    'email'        => $_POST['temail'] ?? null,
    'national_id'  => $_POST['id_no'] ?? null,
    'tenant_reg'   => $_POST['tenant_reg'] ?? null,
  ];

  $profileData = [
    'income'             => $_POST['income'] ?? null,
    'job_title'          => $_POST['job_title'] ?? null,
    'job_location'       => $_POST['job_location'] ?? null,
    'casual_job'         => $_POST['casual_job'] ?? 0,
    'business_name'      => $_POST['business_name'] ?? null,
    'business_location'  => $_POST['business_location'] ?? null,
  ];

  // Assume you already have the unit number available
  // Example: fetched earlier from building_units table
  // $unitNumber = 'B25';

  $tenancyData = [
    'unit_id'            => $_POST['id'] ?? null,

    // Generate a unique, human-readable account number per tenancy
    'account_no'         => $unit_number . '-' . time(),
    // Example output: B25-1700000000

    'leasing_period'     => $_POST['leasing_period'] ?? null,
    'leasing_start_date' => $_POST['leasing_start_date'] ?? null,
    'leasing_end_date'   => $_POST['leasing_end_date'] ?? null,
    'move_in_date'       => $_POST['move_in_date'] ?? null,
    'move_out_date'      => $_POST['move_out_date'] ?? null,
    'status'             => 'Active'
  ];


  // --------------------------------------------
  // VALIDATION (basic example)
  // --------------------------------------------
  $requiredTenantFields = ['first_name', 'last_name', 'phone', 'email', 'national_id'];
  $requiredTenancyFields = ['unit_id', 'account_no', 'leasing_period', 'leasing_start_date', 'move_in_date'];
  $missing = [];

  foreach ($requiredTenantFields as $f) if (empty($tenantData[$f])) $missing[] = $f;
  foreach ($requiredTenancyFields as $f) if (empty($tenancyData[$f])) $missing[] = $f;
  foreach ($files as $key => $file) if (empty($file)) $missing[] = $key;


  try {
    $pdo->beginTransaction();

    // --------------------------------------------
    // CHECK OR INSERT TENANT
    // --------------------------------------------
    $stmt = $pdo->prepare("SELECT id FROM tenants WHERE national_id = :national_id LIMIT 1");
    $stmt->execute([':national_id' => $tenantData['national_id']]);
    $tenantId = $stmt->fetchColumn();

    if (!$tenantId) {
      $stmt = $pdo->prepare("
                INSERT INTO tenants 
                (first_name, middle_name, last_name, phone, alt_phone, email, national_id, tenant_reg)
                VALUES (:first_name, :middle_name, :last_name, :phone, :alt_phone, :email, :national_id, :tenant_reg)
            ");
      $stmt->execute($tenantData);
      $tenantId = $pdo->lastInsertId();
    }

    // --------------------------------------------
    // INSERT TENANT PROFILE
    // --------------------------------------------
    $stmt = $pdo->prepare("
            INSERT INTO tenant_profiles
            (tenant_id, income, job_title, job_location, casual_job, business_name, business_location)
            VALUES (:tenant_id, :income, :job_title, :job_location, :casual_job, :business_name, :business_location)
            ON DUPLICATE KEY UPDATE
                income=VALUES(income),
                job_title=VALUES(job_title),
                job_location=VALUES(job_location),
                casual_job=VALUES(casual_job),
                business_name=VALUES(business_name),
                business_location=VALUES(business_location)
        ");
    $stmt->execute(array_merge(['tenant_id' => $tenantId], $profileData));

    // --------------------------------------------
    // INSERT TENANCY
    // --------------------------------------------
    $stmt = $pdo->prepare("
            INSERT INTO tenancies
            (tenant_id, unit_id, account_no, leasing_period, leasing_start_date, leasing_end_date, move_in_date, move_out_date, status)
            VALUES (:tenant_id, :unit_id, :account_no, :leasing_period, :leasing_start_date, :leasing_end_date, :move_in_date, :move_out_date, :status)
        ");
    $stmt->execute(array_merge(['tenant_id' => $tenantId], $tenancyData));
    $tenancyId = $pdo->lastInsertId();

    // --------------------------------------------
    // UPLOAD DOCUMENTS TO TENANT_DOCUMENTS TABLE
    // --------------------------------------------
    $stmt = $pdo->prepare("
            INSERT INTO tenant_documents (tenant_id, tenancy_id, document_type, file_path)
            VALUES (:tenant_id, :tenancy_id, :doc_type, :file_path)
        ");
    foreach ($files as $type => $path) {
      $stmt->execute([
        ':tenant_id' => $tenantId,
        ':tenancy_id' => $tenancyId,
        ':doc_type'  => $type,
        ':file_path' => $path
      ]);
    }

    // --------------------------------------------
    // UPDATE UNIT OCCUPANCY
    // --------------------------------------------
    $stmt = $pdo->prepare("UPDATE building_units SET occupancy_status='Occupied' WHERE id=:unit_id");
    $stmt->execute([':unit_id' => $tenancyData['unit_id']]);

    $pdo->commit();

    header("Location: single_units_tenants.php?success=1");
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
     echo "Transaction failed. Please try again.";
    // For debugging (optional):
    echo $e->getMessage();
    exit;
  }
}
