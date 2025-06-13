<?php
require '../db/connect.php'; // Update path to your DB connection file

$unit = $_GET['unit'] ?? '';
$year = $_GET['year'] ?? '';
$month = $_GET['month'] ?? '';

$where = [];
$params = [];

if (!empty($unit)) {
  $where[] = "unit_type = ?";
  $params[] = $unit;
}
if (!empty($year)) {
  $where[] = "`year` = ?";
  $params[] = $year;
}
if (!empty($month)) {
  $where[] = "`month` = ?";
  $params[] = $month;
}

$sql = "SELECT * FROM tenant_rent_summary";
if (!empty($where)) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY building_name, tenant_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tenants = $stmt->fetchAll();

$currentBuilding = '';
foreach ($tenants as $tenant):
    $building = $tenant['building_name'] ?? '';

    if ($building !== $currentBuilding):
        $currentBuilding = $building;
        echo '<tr class="table-group-header bg-light">
                <td colspan="6" style="font-weight: bold; color: #007bff;">' . htmlspecialchars($currentBuilding) . '</td>
              </tr>';
    endif;

    $nameParts = explode(" ", $tenant['tenant_name'] ?? '');
    $firstName = $nameParts[0] ?? '';
    $middleName = $nameParts[1] ?? '';
    $unitCode = htmlspecialchars($tenant['unit_code'] ?? '');
    $amount = number_format((float)($tenant['amount_paid'] ?? 0), 2);
    $balances = number_format((float)($tenant['balances'] ?? 0), 2);
    $penalty = number_format((float)($tenant['penalty'] ?? 0), 2);
    $arrears = number_format((float)($tenant['arrears'] ?? 0), 2);
    $overpayment = number_format((float)($tenant['overpayment'] ?? 0), 2);
    $penaltyDays = (int)($tenant['penalty_days'] ?? 0);
    $paymentDate = !empty($tenant['payment_date']) ? date("d-F", strtotime($tenant['payment_date'])) : '';

    echo '<tr>
            <th>
              <div class="d-flex justify-content-between">
                <div>' . htmlspecialchars("$firstName $middleName") . '</div>
                <div class="value" style="color:#FFC107">&nbsp;' . $unitCode . '</div>
              </div>
            </th>
            <td><div class="rent paid"><div>KSH&nbsp;' . $amount . '</div><div class="date late">' . $paymentDate . '</div></div></td>
            <td class="rent collected">KSH&nbsp;' . $balances . '</td>
            <td><div class="rent penalit">KSH&nbsp;' . $penalty . ' (<span class="rent lateDays">-' . $penaltyDays . '</span>)</div></td>
            <td class="rent collected">KSH&nbsp;' . $arrears . '</td>
            <td class="rent overpayment">KSH&nbsp;' . $overpayment . '</td>
            <td>
              <button class="btn view" data-bs-toggle="modal" data-bs-target="#tenantProfileModal">View</button>
              <button class="btn print" onclick="window.open(\'print-receipt.php?tenant_id=' . $tenant['id'] . '\', \'_blank\')">
              <i class="fas fa-file-invoice"></i>Receipt</button>
            </td>
          </tr>';
endforeach;
?>
