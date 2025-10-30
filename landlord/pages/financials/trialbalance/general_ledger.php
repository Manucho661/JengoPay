<?php
include '../../db/connect.php';

// Capture filters
$from_date = $_GET['from_date'] ?? '';
$to_date   = $_GET['to_date'] ?? '';
$account   = $_GET['account'] ?? ''; // account name from URL or dropdown

$where  = [];
$params = [];

// Filter by date range
if (!empty($from_date) && !empty($to_date)) {
    $where[] = "DATE(je.created_at) BETWEEN :from AND :to";
    $params[':from'] = $from_date;
    $params[':to']   = $to_date;
}

// Filter by account name
if (!empty($account)) {
    $where[] = "a.account_name = :account_name";
    $params[':account_name'] = $account;
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Fetch ledger data (only for the selected account)
$sql = "
    SELECT 
        je.created_at,
        je.reference,
        je.description,
        a.account_code,
        a.account_name,
        jl.debit,
        jl.credit
    FROM journal_entries je
    INNER JOIN journal_lines jl ON je.id = jl.journal_entry_id
    INNER JOIN chart_of_accounts a ON jl.account_id = a.account_code
    $whereSql
    ORDER BY je.created_at, je.id
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ledgerRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all accounts for dropdown
$accounts = $pdo->query("
    SELECT account_code, account_name 
    FROM chart_of_accounts 
    ORDER BY account_name ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>General Ledger</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2 style="color:#FFC107;">General Ledger</h2>

<!-- Filters -->
<form method="get" class="row g-3 mb-4">
  <div class="col-md-3">
    <label for="from_date" class="form-label">From Date</label>
    <input type="date" id="from_date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="form-control">
  </div>
  <div class="col-md-3">
    <label for="to_date" class="form-label">To Date</label>
    <input type="date" id="to_date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="form-control">
  </div>
  <div class="col-md-3">
    <label for="account" class="form-label">Account</label>
    <select id="account" name="account" class="form-select">
      <option value="">-- All Accounts --</option>
      <?php foreach ($accounts as $acc): ?>
        <option value="<?= htmlspecialchars($acc['account_name']) ?>" <?= $account == $acc['account_name'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($acc['account_name']) ?> (<?= htmlspecialchars($acc['account_code']) ?>)
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button type="submit" class="btn w-100" style="color:#FFC107; background-color:#00192D;">Filter</button>
  </div>
</form>

<?php
if (empty($ledgerRows)) {
    echo '<div class="alert alert-warning">No ledger entries found for the selected account.</div>';
} else {
    $runningBalance = 0;
    $first = $ledgerRows[0];
    echo "<h5 class='mt-4 text-dark fw-bold'>{$first['account_name']} ({$first['account_code']})</h5>";
    ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Date</th>
          <th>Reference</th>
          <th>Description</th>
          <th>Account</th>
          <th class='text-end'>Debit (KSH)</th>
          <th class='text-end'>Credit (KSH)</th>
          <th class='text-end'>Running Balance</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($ledgerRows as $row): ?>
          <?php $runningBalance += $row['debit'] - $row['credit']; ?>
          <tr>
            <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
            <td><?= htmlspecialchars($row['reference']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['account_name']) ?></td>
            <td class='text-end'><?= number_format($row['debit'], 2) ?></td>
            <td class='text-end'><?= number_format($row['credit'], 2) ?></td>
            <td class='text-end'><?= number_format($runningBalance, 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
