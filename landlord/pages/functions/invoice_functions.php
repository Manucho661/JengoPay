<?php
// functions/invoice_functions.php

function getUnitMonthlyRent($pdo, $unit_id) {
    try {
        $stmt = $pdo->prepare("SELECT monthly_rent FROM building_units WHERE id = ?");
        $stmt->execute([$unit_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? floatval($result['monthly_rent']) : 0;
    } catch (Exception $e) {
        error_log("Error fetching monthly rent: " . $e->getMessage());
        return 0;
    }
}

function getTenantUnitId($pdo, $tenant_id) {
    try {
        $stmt = $pdo->prepare("SELECT unit_id FROM tenants WHERE id = ?");
        $stmt->execute([$tenant_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['unit_id'] : null;
    } catch (Exception $e) {
        error_log("Error fetching tenant unit: " . $e->getMessage());
        return null;
    }
}

function addRentToInvoice($pdo, $tenant_id, $invoice_id, $month = null, $year = null) {
    if (!$month) $month = date('m');
    if (!$year) $year = date('Y');
    
    // Get tenant's unit_id
    $unit_id = getTenantUnitId($pdo, $tenant_id);
    
    if (!$unit_id) {
        return false; // Tenant has no unit assigned
    }
    
    // Get monthly rent for the unit
    $monthly_rent = getUnitMonthlyRent($pdo, $unit_id);
    
    if ($monthly_rent <= 0) {
        return false; // No rent set for this unit
    }
    
    // Get unit details for description
    $stmt = $pdo->prepare("
        SELECT bu.unit_number, b.building_name 
        FROM building_units bu
        LEFT JOIN buildings b ON bu.building_id = b.id
        WHERE bu.id = ?
    ");
    $stmt->execute([$unit_id]);
    $unit = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calculate VAT (16% VAT Inclusive)
    $vat_rate = 0.16;
    $total_amount = $monthly_rent;
    $vat_amount = $total_amount * ($vat_rate / (1 + $vat_rate));
    $net_amount = $total_amount - $vat_amount;
    
    // Add rent item to invoice
    $stmt = $pdo->prepare("
        INSERT INTO invoice_items 
        (invoice_id, item_name, description, quantity, unit_price, 
         tax_type, tax_rate, tax_amount, total_amount, account_code, created_at)
        VALUES 
        (?, 'Rent', ?, 1, ?, 'VAT Inclusive', ?, ?, ?, 500, NOW())
    ");
    
    $description = "Monthly Rent for " . date('F Y', strtotime("$year-$month-01")) . 
                   " - Unit " . ($unit['unit_number'] ?? '') . 
                   ", " . ($unit['building_name'] ?? '');
    
    $stmt->execute([
        $invoice_id,
        $description,
        $net_amount,
        $vat_rate,
        $vat_amount,
        $total_amount
    ]);
    
    return $pdo->lastInsertId();
}
?>