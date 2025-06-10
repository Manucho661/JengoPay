<?php
// Tell browser to treat the output as a downloadable PDF file
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="building_rent_details.pdf"');

// Simulate basic PDF content (not a real PDF format, but trick for simple download)
echo "<h2>Rent Summary Report</h2>";
echo "<table border='1' cellspacing='0' cellpadding='5'>
        <tr><th>Building</th><th>Collected</th><th>Penalties</th><th>Arrears</th><th>Overpayment</th></tr>";

include '../db/connect.php';

$stmt = $pdo->query("SELECT * FROM building_rent_summary");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
            <td>{$row['building_name']}</td>
            <td>{$row['amount_collected']}</td>
            <td>{$row['penalties']}</td>
            <td>{$row['arrears']}</td>
            <td>{$row['overpayment']}</td>
          </tr>";
}

echo "</table>";
exit;


