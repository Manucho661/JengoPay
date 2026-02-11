<?php
// Session variables to use
$userId = $_SESSION['user']['id'];

// Fetch landlord ID linked to the logged-in user
$stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
$stmt->execute([$userId]);
$landlord = $stmt->fetch();

// Check if landlord exists for the user
if (!$landlord) {
    throw new Exception("Landlord account not found for this user.");
}

$landlord_id = $landlord['id']; // Store the landlord_id from the session
$userId = (int)$_SESSION['user']['id'];

// 2) Fetch landlord id linked to logged-in user
$landlordStmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ? LIMIT 1");
$landlordStmt->execute([$userId]);
$landlordId = $landlordStmt->fetchColumn();

if (!$landlordId) {
    throw new Exception("Landlord account not found for this user.");
}

// 3) Get unit category id (single_unit)
$categoryStmt = $pdo->prepare("
    SELECT id
    FROM unit_categories
    WHERE category_name = :category_name
    LIMIT 1
");
$categoryStmt->execute([':category_name' => 'single_unit']);
$unitCategoryId = $categoryStmt->fetchColumn();

if (!$unitCategoryId) {
    throw new Exception("Unit category not found.");
}

// 4) Fetch building units for this landlord + category, with building name
$sql = "
SELECT 
bu.*,
b.building_name
FROM building_units bu
INNER JOIN buildings b ON bu.building_id = b.id
WHERE bu.unit_category_id = :unit_category_id
AND bu.landlord_id = :landlord_id
ORDER BY b.building_name ASC, bu.unit_number ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':unit_category_id' => (int)$unitCategoryId,
    ':landlord_id'      => (int)$landlordId,
]);

// 5) Fetch total counts of each status (Occupied, Vacant, Under Maintenance) for the building units
$occupiedStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM building_units 
    WHERE landlord_id = :landlord_id
    AND unit_category_id = :unit_category_id
    AND occupancy_status = 'Occupied'
");
$occupiedStmt->execute([
    ':landlord_id' => $landlordId,
    ':unit_category_id' => $unitCategoryId
]);
$totalOccupied = $occupiedStmt->fetchColumn();

$vacantStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM building_units 
    WHERE landlord_id = :landlord_id
    AND unit_category_id = :unit_category_id
    AND occupancy_status = 'Vacant'
");
$vacantStmt->execute([
    ':landlord_id' => $landlordId,
    ':unit_category_id' => $unitCategoryId
]);
$totalVacant = $vacantStmt->fetchColumn();

$underMaintenanceStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM building_units 
    WHERE landlord_id = :landlord_id
    AND unit_category_id = :unit_category_id
    AND occupancy_status = 'Under Maintenance'
");
$underMaintenanceStmt->execute([
    ':landlord_id' => $landlordId,
    ':unit_category_id' => $unitCategoryId
]);
$totalUnderMaintenance = $underMaintenanceStmt->fetchColumn();


try {
    // Continue with the rest of your code if needed...
} catch (Exception $e) {
    // Handle any exceptions that might occur
}
