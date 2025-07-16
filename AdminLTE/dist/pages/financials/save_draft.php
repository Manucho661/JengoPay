<?php
require_once '../db/connect.php';

$invoiceNumber = $_POST['invoice_number'] ?? '';
$buildingId = $_POST['building_id'] ?? null;
$tenantId = $_POST['tenant'] ?? null;
$invoiceDate = $_POST['invoice_date'] ?? null;
$dueDate = $_POST['due_date'] ?? null;

// Save as a draft with minimal data (adjust your table structure accordingly)
$sql = "INSERT INTO invoice (invoice_number, building_id, tenant, invoice_date, due_date, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'draft', NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([$invoiceNumber, $buildingId, $tenantId, $invoiceDate, $dueDate]);

echo "Draft saved.";
