<?php
 include '../db/connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unitNumber = $_POST['unit_number'];
    $meterType = $_POST['meter_type'];
    $readingDate = $_POST['reading_date'];
    $previous = (float) $_POST['previous_reading'];
    $current = (float) $_POST['current_reading'];
    $consumptionUnits = $current - $previous;

    // Define your unit rate
    $unitRate = ($meterType === 'Water') ? 50 : 20;
    $amount = $consumptionUnits * $unitRate;

    // 1. Insert meter reading
    $stmt = $pdo->prepare("INSERT INTO meter_readings (unit_number, meter_type, reading_date, previous_reading, current_reading, consumption_units) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$unitNumber, $meterType, $readingDate, $previous, $current, $consumptionUnits]);

    // 2. Create or fetch invoice
    $stmt = $pdo->prepare("SELECT id FROM invoices WHERE unit_number = ? AND status = 'pending' LIMIT 1");
    $stmt->execute([$unitNumber]);
    $invoice = $stmt->fetch();

    if (!$invoice) {
        $stmt = $pdo->prepare("INSERT INTO invoices (unit_number, created_at, status) VALUES (?, NOW(), 'pending')");
        $stmt->execute([$unitNumber]);
        $invoiceId = $pdo->lastInsertId();
    } else {
        $invoiceId = $invoice['id'];
    }

    // 3. Add charge to invoice
    $stmt = $pdo->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?)");
    $description = $meterType . ' Meter Reading (' . $readingDate . ')';
    $stmt->execute([$invoiceId, $description, $consumptionUnits, $unitRate, $amount]);

    // 4. Redirect
    // header("Location: /originalTwo/AdminLTE/dist/pages/property/financials/invoices.php?id=" . $invoiceId);
    exit;
}
?>
