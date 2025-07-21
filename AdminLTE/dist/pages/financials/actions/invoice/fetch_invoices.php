<?php
require_once '../../db/connect.php';

// Query to fetch invoice data with related building and tenant info
$stmt = $pdo->query("
    SELECT
        i.id,
        i.invoice_number,
        b.building_name AS property_name,
        i.tenant,
        i.invoice_date,
        i.taxes,
        i.total
    FROM invoice i
    LEFT JOIN buildings b ON i.building_id = b.building_id
    LEFT JOIN tenants t ON i.tenant = t.id
    ORDER BY i.invoice_date DESC
");

$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($invoices);
?>
