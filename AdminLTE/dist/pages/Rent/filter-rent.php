<?php
include '../db/connect.php';

$building = $_POST['building'] ?? '';
$year = $_POST['year'] ?? date('Y');
$month = $_POST['month'] ?? '';

$query = "SELECT * FROM building_rent_summary WHERE 1=1";

$params = [];

if ($building !== '') {
    $query .= " AND building_name = ?";
    $params[] = $building;
}
if ($year !== '') {
    $query .= " AND year = ?";
    $params[] = $year;
}
if ($month !== '') {
    $query .= " AND month = ?";
    $params[] = $month;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$totalCollected = $totalBalances = $totalPenalties = $totalArrears = $totalOverpayment = 0;

while ($row = $stmt->fetch()):
    $building = htmlspecialchars($row['building_name']);
    $balances = (float)$row['balances'];
    $collected = (float)$row['amount_collected'];
    $penalties = (float)$row['penalties'];
    $arrears = (float)$row['arrears'];
    $overpayment = (float)$row['overpayment'];

    $totalCollected += $collected;
    $totalBalances +=  $balances;
    $totalPenalties += $penalties;
    $totalArrears += $arrears;
    $totalOverpayment += $overpayment;
?>
<tr>
    <th><?= $building ?></th>
    <td class="rent paid">KSH&nbsp;<?= number_format($collected, 2) ?></td>
    <td class="rent balances">KSH&nbsp;<?= number_format($balances, 2) ?></td>
    <td><div class="rent penalit">KSH&nbsp;<?= number_format($penalties, 2) ?></div></td>
    <td class="rent collected">KSH&nbsp;<?= number_format($arrears, 2) ?></td>
    <td class="rent overpayment">KSH&nbsp;<?= number_format($overpayment, 2) ?></td>
    <td>
        <button class="btn view">
            <a class="view-link" href="building-rent.php?building=<?= urlencode($building); ?>">View</a>
        </button>
    </td>
</tr>
<?php endwhile; ?>
<tr style="font-weight: bold; background-color: #f0f0f0;">
    <td>Total</td>
    <td class="rent paid">KSH&nbsp;<?= number_format($totalCollected, 2)?></td>
    <td class="rent paid">KSH&nbsp;<?= number_format($totalBalances, 2) ?></td>
    <td><div class="rent penalit">KSH&nbsp;<?= number_format($totalPenalties, 2) ?></div></td>
    <td class="rent collected">KSH&nbsp;<?= number_format($totalArrears, 2) ?></td>
    <td class="rent overpayment">KSH&nbsp;<?= number_format($totalOverpayment, 2) ?></td>
    <td></td>
</tr>
