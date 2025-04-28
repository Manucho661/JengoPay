<?php
include '../db/connect.php';

// Get selected building from query
$building = isset($_GET['building']) ? $_GET['building'] : 'All Buildings';

// Base query
$sql = "SELECT
            users.id,
            users.name,
            users.email,
            tenants.phone_number,
            tenants.user_id,
            tenants.residence,
            tenants.id_no,
            tenants.unit,
            tenants.status
        FROM tenants
        INNER JOIN users ON tenants.user_id = users.id";

// Add filter if a specific building is selected
if ($building !== 'All Buildings') {
    $sql .= " WHERE tenants.residence = :building";
}

$stmt = $pdo->prepare($sql);

if ($building !== 'All Buildings') {
    $stmt->bindParam(':building', $building, PDO::PARAM_STR);
}

$stmt->execute();
$tenants = $stmt->fetchAll();

// Now generate the table rows exactly like your existing structure
if (!empty($tenants)) {
    foreach ($tenants as $tenant) {
        ?>
        <tr onclick="goToDetails(<?= htmlspecialchars($tenant['user_id']) ?>)">
            <td><?= htmlspecialchars($tenant['name']) ?></td>
            <td><?= htmlspecialchars($tenant['id_no']) ?></td>
            <td>
                <div><?= htmlspecialchars($tenant['residence']) ?></div>
                <div style="color: green;"><?= htmlspecialchars($tenant['unit']) ?></div>
            </td>
            <td>
                <div class="phone"><i class="fas fa-phone icon"></i> <?= htmlspecialchars($tenant['phone_number']) ?></div>
                <div class="email"><i class="fa fa-envelope icon"></i> <?= htmlspecialchars($tenant['email']) ?></div>
            </td>
            <td>
                <button class="status completed">
                    <i class="fa fa-check-circle"></i> <?= htmlspecialchars($tenant['status']) ?>
                </button>
            </td>
            <td>
                <button onclick="handleDelete(event, <?= htmlspecialchars($tenant['user_id']) ?>, 'users');"
                        class="btn btn-sm"
                        style="background-color: #00192D; color:white">
                    <i class="fa fa-arrow-right" data-toggle="tooltip" title="Vacant Tenant from House"></i>
                </button>

                <button class="btn btn-sm" style="background-color: #AF2A28; color:#fff;">
                    <i class="fa fa-comment" data-toggle="tooltip" title="Send SMS"></i>
                </button>

                <button style="background-color: #F74B00; color:#fff;" class="btn btn-sm" data-toggle="tooltip" title="Send Email">
                    <i class="fa fa-envelope"></i>
                </button>
            </td>
        </tr>
        <?php
    }
} else {
    ?>
    <tr>
        <td colspan="6">No tenants found.</td>
    </tr>
    <?php
}
?>
