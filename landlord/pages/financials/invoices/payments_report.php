<?php
include "../../db/connect.php";

$stmt = $pdo->query("SELECT * FROM payments ORDER BY payment_date DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Payments Report</title>
  <style>
    table {border-collapse: collapse; width: 100%;}
    th, td {border: 1px solid #ccc; padding: 8px; text-align: left;}
    th {background: #f4f4f4;}
  </style>
</head>
<body>
  <h2>Payments Report</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Tenant</th><th>Invoice</th><th>Amount</th>
        <th>Method</th><th>Date</th><th>Reference</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['tenant']) ?></td>
          <td><?= $r['invoice_id'] ?></td>
          <td><?= $r['amount'] ?></td>
          <td><?= $r['payment_method'] ?></td>
          <td><?= $r['payment_date'] ?></td>
          <td><?= $r['reference_number'] ?></td>
          <td><?= $r['status'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
