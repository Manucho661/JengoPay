<?php
if (isset($_POST['submit_reading'])) {

    require_once '../db/connect.php';

    // 🔐 Sanitize & collect inputs
    $unit_id          = (int) $_POST['id'];
    $reading_date     = $_POST['reading_date'];
    $meter_type       = $_POST['meter_type']; // Water / Electricity
    $current_reading  = (float) $_POST['current_reading'];
    $previous_reading = (float) ($_POST['previous_reading'] ?? 0);
    $units_consumed   = (float) $_POST['units_consumed'];
    $cost_per_unit    = (float) $_POST['cost_per_unit'];
    $final_bill       = (float) $_POST['final_bill'];

    // OPTIONAL: resolve tenant_id if unit is occupied
    $tenant_id = 0;
    $tenantStmt = $pdo->prepare("
        SELECT id FROM tenants 
        WHERE unit_id = ? AND tenant_status = 'Active'
        LIMIT 1
    ");
    $tenantStmt->execute([$unit_id]);
    if ($tenant = $tenantStmt->fetch()) {
        $tenant_id = $tenant['id'];
    }

    // 🛡️ Safety check
    if (!in_array($meter_type, ['Water', 'Electricity'])) {
        die('Invalid meter type');
    }

    // ✅ INSERT INTO bills
    $stmt = $pdo->prepare("
        INSERT INTO bills (
            unit_id,
            tenant_id,
            bill_name,
            quantity,
            unit_price,
            meter_type,
            sub_total,
            created_at
        ) VALUES (
            :unit_id,
            :tenant_id,
            :bill_name,
            :quantity,
            :unit_price,
            :meter_type,
            :sub_total,
            :created_at
        )
    ");

    $stmt->execute([
        ':unit_id'    => $unit_id,
        ':tenant_id'  => $tenant_id,
        ':bill_name'  => $meter_type,        // 👈 IMPORTANT
        ':quantity'   => $units_consumed,
        ':unit_price' => $cost_per_unit,
        ':meter_type' => $meter_type,
        ':sub_total'  => $final_bill,
        ':created_at' => $reading_date
    ]);

    // ✅ Success feedback
    echo "<script>
        alert('Meter reading saved successfully');
        window.location.href = 'single_units.php';
    </script>";
}