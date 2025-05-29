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
    // Validate and sanitize input
    $requiredFields = [ 'tenant_id', 'income_source', 'employer', 'job_title'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
            exit;
        }
    }

    $userId = intval($_POST['user_id']);
    $tenantId = intval($_POST['tenant_id']);
    $incomeSource = trim($_POST['income_source']);
    $employer = trim($_POST['employer']);
    $jobTitle = trim($_POST['job_title']);

    $stmt = $pdo->prepare("
        UPDATE income_source 
        SET income_type = :income_type,
            work_place = :employer,
            job_title = :job_title
        WHERE tenant_id = :tenant_id
    ");

    $stmt->bindParam(':income_type', $incomeSource);
    $stmt->bindParam(':employer', $employer);
    $stmt->bindParam(':job_title', $jobTitle);
    $stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        ob_end_clean();
        echo json_encode(['success' => true]);
    } else {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Failed to update record.']);
    }
} catch (PDOException $e) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
