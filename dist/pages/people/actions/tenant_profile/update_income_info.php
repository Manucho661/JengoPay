<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../../db/connect.php';
header('Content-Type: application/json');

if (!isset($pdo)) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

try {
    $requiredFields = ['tenant_id', 'income_source', 'employer', 'job_title'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
            exit;
        }
    }

    $tenantId = intval($_POST['tenant_id']);
    $incomeType = trim($_POST['income_source']);
    $placeOfWork = trim($_POST['employer']);
    $jobTitle = trim($_POST['job_title']);

    // Check if tenant already has an income_source row
    $checkStmt = $pdo->prepare("SELECT id FROM income_source WHERE tenant_id = ?");
    $checkStmt->execute([$tenantId]);

    if ($checkStmt->fetch()) {
        // Update existing
        $updateStmt = $pdo->prepare("
            UPDATE income_source 
            SET income_type = :income_type,
                place_of_work = :place_of_work,
                job_title = :job_title
            WHERE tenant_id = :tenant_id
        ");
    } else {
        // Insert new
        $updateStmt = $pdo->prepare("
            INSERT INTO income_source (tenant_id, income_type, place_of_work, job_title)
            VALUES (:tenant_id, :income_type, :place_of_work, :job_title)
        ");
    }

    $updateStmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);
    $updateStmt->bindParam(':income_type', $incomeType);
    $updateStmt->bindParam(':place_of_work', $placeOfWork);
    $updateStmt->bindParam(':job_title', $jobTitle);

    if ($updateStmt->execute()) {
        ob_end_clean();
        echo json_encode(['success' => true]);
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Failed to save income info.']);
    }

} catch (PDOException $e) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
