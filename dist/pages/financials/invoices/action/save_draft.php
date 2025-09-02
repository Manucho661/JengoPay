<?php
header('Content-Type: application/json');
require_once '../../../db/connect.php';

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Set default values for all fields
$defaultData = [
    'invoice_number' => '',
    'building_id' => null,
    'tenant' => 0,
    'account_item' => '',
    'description' => '',
    'quantity' => '0',
    'unit_price' => '0',
    'taxes' => '0',
    'sub_total' => '0',
    'total' => '0',
    'notes' => '',
    'terms_conditions' => '',
    'invoice_date' => null,
    'due_date' => null,
    'status' => 'draft',
    'payment_status' => 'unpaid'
];

// Merge with incoming data
$data = array_merge($defaultData, $data);

try {
    // Prepare SQL with all possible fields
    $stmt = $pdo->prepare("INSERT INTO invoice (
        invoice_number, invoice_date, due_date, building_id, tenant,
        account_item, description, quantity, unit_price, taxes,
        sub_total, total, notes, terms_conditions, status, payment_status, created_at
    ) VALUES (
        :invoice_number, :invoice_date, :due_date, :building_id, :tenant,
        :account_item, :description, :quantity, :unit_price, :taxes,
        :sub_total, :total, :notes, :terms_conditions, :status, :payment_status, NOW()
    )");

    // Bind parameters with proper NULL handling
    $params = [
        ':invoice_number' => $data['invoice_number'],
        ':invoice_date' => !empty($data['invoice_date']) ? $data['invoice_date'] : null,
        ':due_date' => !empty($data['due_date']) ? $data['due_date'] : null,
        ':building_id' => $data['building_id'],
        ':tenant' => $data['tenant'],
        ':account_item' => $data['account_item'],
        ':description' => $data['description'],
        ':quantity' => $data['quantity'],
        ':unit_price' => $data['unit_price'],
        ':taxes' => $data['taxes'],
        ':sub_total' => $data['sub_total'],
        ':total' => $data['total'],
        ':notes' => $data['notes'],
        ':terms_conditions' => $data['terms_conditions'],
        ':status' => $data['status'],
        ':payment_status' => $data['payment_status']
    ];

    // Execute with parameters
    $stmt->execute($params);

    // Return success with all saved data for verification
    echo json_encode([
        'success' => true,
        'invoice_id' => $pdo->lastInsertId(),
        'invoice_number' => $data['invoice_number'],
        'saved_data' => $params, // Return all saved values for debugging
        'message' => 'Draft saved successfully'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'error_info' => $e->errorInfo,
        'received_data' => $data,
        'prepared_params' => $params ?? null
    ]);
}
?>