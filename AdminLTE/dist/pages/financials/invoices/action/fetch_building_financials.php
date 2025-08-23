<?php
include '../db/connect.php';

$building_id = $_GET['building_id'] ?? null;
$whereClause = $building_id && $building_id !== 'all' ? "AND building_id = ?" : "";
$params = $building_id && $building_id !== 'all' ? [$building_id] : [];

// --- Income Mapping (account_item from invoice_items) ---
$incomeAccounts = [
    '500' => 'formattedRent',
    '505' => 'formattedLateFees',
    '510' => 'formattedWaterIncome',
    '515' => 'formattedGarbageIncome',
    '520' => 'formattedCommissionIncome',
    '525' => 'formattedOtherIncome'
];

foreach ($incomeAccounts as $code => $varName) {
    $$varName = number_format(fetchAmount($pdo, "invoice_items", "account_item", $code, $whereClause, $params), 2);
}

// --- Expense Mapping (item_account_code from expense_items) ---
$expenseAccounts = [
    '600' => 'formattedMaintenance',
    '605' => 'formattedSalaryTotal',
    '610' => 'formattedElectricity',
    '615' => 'formattedWaterExpense',
    '620' => 'formattedGarbageExpense',
    '625' => 'formattedInternetExpense',
    '630' => 'formattedSecurityExpense',
    '635' => 'formattedSoftwareExpense',
    '640' => 'formattedMarketingExpense',
    '645' => 'formattedLegalExpense',
    '655' => 'formattedLoanInterest',
    '660' => 'formattedBankCharges',
    '665' => 'formattedOtherExpense'
];

foreach ($expenseAccounts as $code => $varName) {
    $$varName = number_format(fetchAmount($pdo, "expense_items", "item_account_code", $code, $whereClause, $params), 2);
}

// --- Total Income ---
$totalIncome = array_sum(array_map(function($v) {
    return (float) str_replace(',', '', $v);
}, array_map(function($code) use ($incomeAccounts) {
    return $GLOBALS[$incomeAccounts[$code]];
}, array_keys($incomeAccounts))));

// --- Total Expenses ---
$totalExpenses = array_sum(array_map(function($v) {
    return (float) str_replace(',', '', $v);
}, array_map(function($code) use ($expenseAccounts) {
    return $GLOBALS[$expenseAccounts[$code]];
}, array_keys($expenseAccounts))));

// --- Net Profit ---
$formattedTotalIncome = number_format($totalIncome, 2);
$formattedTotalExpenses = number_format($totalExpenses, 2);
$formattedNetProfit = number_format($totalIncome - $totalExpenses, 2);

// --- HTML Table Output ---
?>

<!-- INCOME SECTION -->
<tr class="category"><td style="color:green;"><b>Income</b></td></tr>
<tr><td>Rental Income</td><td>Ksh <?= $formattedRent ?></td></tr>
<tr><td>Late Payment Fees</td><td>Ksh <?= $formattedLateFees ?></td></tr>
<tr><td>Water Charges (Revenue)</td><td>Ksh <?= $formattedWaterIncome ?></td></tr>
<tr><td>Garbage Collection Fees</td><td>Ksh <?= $formattedGarbageIncome ?></td></tr>
<tr><td>Commissions and Management Fees</td><td>Ksh <?= $formattedCommissionIncome ?></td></tr>
<tr><td>Other Income</td><td>Ksh <?= $formattedOtherIncome ?></td></tr>
<tr class="category"><td><b>Total Income</b></td><td><b>Ksh <?= $formattedTotalIncome ?></b></td></tr>

<!-- EXPENSE SECTION -->
<tr class="category"><td style="color:green;"><b>Expenses</b></td></tr>
<tr><td>Maintenance and Repair Costs</td><td>Ksh <?= $formattedMaintenance ?></td></tr>
<tr><td>Staff Salaries and Wages</td><td>Ksh <?= $formattedSalaryTotal ?></td></tr>
<tr><td>Electricity Expense</td><td>Ksh <?= $formattedElectricity ?></td></tr>
<tr><td>Water Expense</td><td>Ksh <?= $formattedWaterExpense ?></td></tr>
<tr><td>Garbage Collection Expense</td><td>Ksh <?= $formattedGarbageExpense ?></td></tr>
<tr><td>Internet Expense</td><td>Ksh <?= $formattedInternetExpense ?></td></tr>
<tr><td>Security Expense</td><td>Ksh <?= $formattedSecurityExpense ?></td></tr>
<tr><td>Software Subscription</td><td>Ksh <?= $formattedSoftwareExpense ?></td></tr>
<tr><td>Marketing & Advertising</td><td>Ksh <?= $formattedMarketingExpense ?></td></tr>
<tr><td>Legal & Compliance</td><td>Ksh <?= $formattedLegalExpense ?></td></tr>
<tr><td>Loan Interest Payments</td><td>Ksh <?= $formattedLoanInterest ?></td></tr>
<tr><td>Bank / Mpesa Charges</td><td>Ksh <?= $formattedBankCharges ?></td></tr>
<tr><td>Other Expenses</td><td>Ksh <?= $formattedOtherExpense ?></td></tr>
<tr class="category"><td><b>Total Expenses</b></td><td><b>Ksh <?= $formattedTotalExpenses ?></b></td></tr>

<!-- NET PROFIT -->
<tr class="category"><td><b>Net Profit</b></td><td><b>Ksh <?= $formattedNetProfit ?></b></td></tr>

<?php
// --- Reusable fetch function ---
function fetchAmount($pdo, $table, $column, $accountCode, $whereClause, $params) {
  // Use correct column depending on table
  $amountColumn = $table === 'expense_items' ? 'item_untaxed_amount' : 'sub_total';
  $sql = "SELECT SUM($amountColumn) FROM $table WHERE $column = ? $whereClause";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array_merge([$accountCode], $params));
  return $stmt->fetchColumn() ?: 0;
}

?>
