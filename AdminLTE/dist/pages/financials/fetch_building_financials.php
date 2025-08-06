<?php
include '../db/connect.php';

$building_id = $_GET['building_id'] ?? null;
$where = '';
$params = [];

// Apply building filter
if ($building_id && $building_id !== 'all') {
    $where = "WHERE building_id = ?";
    $params[] = $building_id;
}

// Rental Income (description = rent)
$formattedRent = number_format(fetchAmount($pdo, "SELECT SUM(total) FROM invoice_items WHERE description LIKE '%rent%' " . ($where ? "AND building_id = ?" : ""), $params), 2);

// Water Revenue
$formattedWater = number_format(fetchAmount($pdo, "SELECT SUM(total) FROM invoice_items WHERE description LIKE '%water%' " . ($where ? "AND building_id = ?" : ""), $params), 2);

// Garbage Revenue
$formattedGarbage = number_format(fetchAmount($pdo, "SELECT SUM(total) FROM invoice_items WHERE description LIKE '%garbage%' " . ($where ? "AND building_id = ?" : ""), $params), 2);

// Total Income
$numericRent = (float) str_replace(',', '', $formattedRent);
$numericWater = (float) str_replace(',', '', $formattedWater);
$numericGarbage = (float) str_replace(',', '', $formattedGarbage);
$totalIncome = $numericRent + $numericWater + $numericGarbage;
$formattedTotalIncome = number_format($totalIncome, 2);

// Expenses can stay zero or be extended if you add expense tables
$formattedMaintenance = number_format(0, 2);
$formattedSalaryTotal = number_format(0, 2);
$formattedTotalExpenses = number_format(0, 2);
$formattedNetProfit = number_format($totalIncome, 2);

// Output HTML table rows:
?>
<tr class="category"><td style="color:green; font-weight:500;"><b>Income</b></td></tr>
<tr><td>Rental Income</td><td>Ksh<?= $formattedRent ?></td></tr>
<tr><td>Water Charges (Revenue)</td><td>Ksh<?= $formattedWater ?></td></tr>
<tr><td>Garbage Collection Fees (Revenue)</td><td>Ksh<?= $formattedGarbage ?></td></tr>
<tr><td>Late Payment Fees</td><td>Ksh 0.00</td></tr>
<tr><td>Commissions and Management Fees</td><td>Ksh 0.00</td></tr>
<tr><td>Other Income (Advertising, Penalties)</td><td>Ksh 0.00</td></tr>
<tr class="category"><td><b>Total Income</b></td><td><b>Ksh<?= $formattedTotalIncome ?></b></td></tr>

<tr class="category"><td style="color:green;"><b>Expenses</b></td></tr>
<tr><td>Maintenance and Repair Costs</td><td>Ksh<?= $formattedMaintenance ?></td></tr>
<tr><td>Staff Salaries and Wages</td><td>Ksh<?= $formattedSalaryTotal ?></td></tr>
<tr><td>Electricity Expense</td><td>Ksh 0.00</td></tr>
<tr><td>Water Expense</td><td>Ksh 0.00</td></tr>
<tr><td>Other Expenses</td><td>Ksh 0.00</td></tr>
<tr class="category"><td><b>Total Expenses</b></td><td><b>Ksh<?= $formattedTotalExpenses ?></b></td></tr>
<tr class="category"><td><b>Net Profit</b></td><td><b>Ksh<?= $formattedNetProfit ?></b></td></tr>

<?php
// Reusable amount fetch function
function fetchAmount($pdo, $sql, $params = []) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() ?: 0;
}
?>
