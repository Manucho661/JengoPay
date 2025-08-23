<?php
include '../db/connect.php'; // Your DB connection

$invoice_number = $_POST['invoice_number'] ?? '';
$vat_type = $_POST['vat_type'] ?? '';
$building_id = $_POST['building_id'] ?? '';

$query = "SELECT * FROM invoice_items WHERE 1";
$params = [];

if (!empty($invoice_number)) {
    $query .= " AND invoice_number = ?";
    $params[] = $invoice_number;
}
if (!empty($vat_type)) {
    $query .= " AND vat_type = ?";
    $params[] = $vat_type;
}
if (!empty($building_id)) {
    $query .= " AND building_id = ?";
    $params[] = $building_id;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);

if ($stmt->rowCount() > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>Invoice Number</th>
            <th>Tenant</th>
            <th>Description</th>
            <th>VAT Type</th>
            <th>Total</th>
          </tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$row['invoice_number']}</td>
                <td>{$row['tenant']}</td>
                <td>{$row['description']}</td>
                <td>{$row['vat_type']}</td>
                <td>{$row['total']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}
?>
