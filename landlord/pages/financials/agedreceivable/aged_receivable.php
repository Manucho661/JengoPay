<?php
require_once '../../db/connect.php'; // your PDO connection file

// Fetch invoices (receivables)
$sql = "SELECT id, invoice_number, tenant, description, created_at, total
        FROM invoice_items";
$stmt = $pdo->query($sql);
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to calculate days overdue
function daysOverdue($date) {
    $today = new DateTime();
    $invoiceDate = new DateTime($date);
    return $today->diff($invoiceDate)->days;
}

// Age buckets
$buckets = [
    '0-30 days' => [],
    '31-60 days' => [],
    '61-90 days' => [],
    '90+ days' => []
];

$totals = [
    '0-30 days' => 0,
    '31-60 days' => 0,
    '61-90 days' => 0,
    '90+ days' => 0,
    'grand' => 0
];

foreach ($invoices as $inv) {
    $days = daysOverdue($inv['created_at']);
    if ($days <= 30) {
        $buckets['0-30 days'][] = $inv;
        $totals['0-30 days'] += $inv['total'];
    } elseif ($days <= 60) {
        $buckets['31-60 days'][] = $inv;
        $totals['31-60 days'] += $inv['total'];
    } elseif ($days <= 90) {
        $buckets['61-90 days'][] = $inv;
        $totals['61-90 days'] += $inv['total'];
    } else {
        $buckets['90+ days'][] = $inv;
        $totals['90+ days'] += $inv['total'];
    }
    $totals['grand'] += $inv['total'];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Aged Receivables</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap + DataTables CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

  <style>
    table { width: 100%; margin-bottom: 30px; }
    th { background: #f4f4f4; }
    h2 { margin-top: 40px; }
    .total-row { font-weight: bold; background: #fafafa; }
  </style>
</head>

<body class="p-4">
  <div class="container-fluid">
    <h1 class="mb-4">Aged Receivables</h1>

    <?php foreach ($buckets as $range => $rows): ?>
      <h2><?php echo $range; ?></h2>
      <table class="table table-bordered table-striped dataTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Invoice No</th>
            <th>Tenant</th>
            <th>Description</th>
            <th>Invoice Date</th>
            <th>Total</th>
            <th>Days Old</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($rows) > 0): ?>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo $r['invoice_number']; ?></td>
                <td><?php echo $r['tenant']; ?></td>
                <td><?php echo $r['description']; ?></td>
                <td><?php echo date("Y-m-d", strtotime($r['created_at'])); ?></td>
                <td><?php echo number_format($r['total'], 2); ?></td>
                <td><?php echo daysOverdue($r['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7">No records</td></tr>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="5" class="text-end">Subtotal</td>
            <td colspan="2"><?php echo number_format($totals[$range], 2); ?></td>
          </tr>
        </tfoot>
      </table>
    <?php endforeach; ?>

    <h2 class="text-end">Grand Total: <?php echo number_format($totals['grand'], 2); ?></h2>
  </div>

  <!-- JS Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

  <script>
    $(document).ready(function() {
      $(".dataTable").DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print'],
        pageLength: 10
      });
    });
  </script>
</body>
</html>
