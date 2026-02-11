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

// var_dump($landlord_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_unit'])) {

    try {
        $pdo->beginTransaction();

        $buildingId = $_SESSION['building_id'] ?? null;
        if (!$buildingId) {
            throw new Exception("Building context missing.");
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE UNIT
        |--------------------------------------------------------------------------
        */
        $check = $pdo->prepare("
            SELECT COUNT(*) 
            FROM building_units 
            WHERE building_id = ? AND unit_number = ?
        ");
        $check->execute([$buildingId, $_POST['unit_number']]);

        if ($check->fetchColumn() > 0) {
            throw new Exception("This unit already exists in the building.");
        }

        /*
        |--------------------------------------------------------------------------
        | UNIT CATEGORY
        |--------------------------------------------------------------------------
        */
        $purpose = $_POST['purpose'];
        $category = 'single_unit';

        if ($purpose === 'Residential') $category = 'residential';
        if (in_array($purpose, ['Office', 'Business', 'Store'])) $category = 'commercial';

        $catStmt = $pdo->prepare("
            SELECT id FROM unit_categories WHERE category_name = ?
        ");
        $catStmt->execute([$category]);
        $unit_category_id = $catStmt->fetchColumn();

        $pdo->prepare("
            INSERT INTO unit_categories (category_name, created_at)
            VALUES (?, NOW())
        ")->execute([$category]);  // Only bind $category here

        /*
|--------------------------------------------------------------------------
| INSERT BUILDING UNIT
|--------------------------------------------------------------------------
*/
        $unitStmt = $pdo->prepare("
    INSERT INTO building_units
    (landlord_id, building_id, unit_category_id, unit_number, purpose, location, monthly_rent, occupancy_status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");
        $unitStmt->execute([
            $landlord_id,
            $buildingId,
            $unit_category_id,
            $_POST['unit_number'],
            $_POST['purpose'],
            $_POST['location'],
            $_POST['monthly_rent'],
            $_POST['occupancy_status']
        ]);

        // Now we can correctly assign $unit_id
        $unit_id = $pdo->lastInsertId(); // Correctly assign the unit_id after insertion

        /*
|--------------------------------------------------------------------------
| INSERT RECURRING BILLS (USING ACCOUNT CODE ONLY)
|--------------------------------------------------------------------------
*/
        if (!empty($_POST['account_code']) && is_array($_POST['account_code'])) {

            $billStmt = $pdo->prepare("
        INSERT INTO recurring_bills
        (unit_id, bill_name, account_code, bill_name_other, quantity, unit_price, subtotal, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");

            foreach ($_POST['account_code'] as $i => $accountCode) {

                if (empty($accountCode)) continue;

                $billOther = $_POST['bill_name_other'][$i] ?? null;
                $qty       = floatval($_POST['quantity'][$i] ?? 0);
                $price     = floatval($_POST['unit_price'][$i] ?? 0);

                if ($qty <= 0 || $price <= 0) continue;

                $subtotal = $qty * $price;

                // Now that $unit_id is properly assigned, execute the bill insert
                $billStmt->execute([
                    $unit_id,  // Now correctly passing the unit_id
                    $accountCode,   // 👈 bill_name now stores account_code
                    $accountCode,   // 👈 account_code
                    $billOther,
                    $qty,
                    $price,
                    $subtotal
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE RENT JOURNAL ENTRY
        |--------------------------------------------------------------------------
        */
        // $payment_method = $_POST['payment_method'] ?? 'cash';
        // $payment_date   = $_POST['payment_date'] ?? date('Y-m-d');
        // $monthly_rent   = floatval($_POST['monthly_rent']);

        // $cash_account = match ($payment_method) {
        //     'mpesa' => 110,
        //     'bank'  => 120,
        //     default => 100,
        // };

        // $entries = [
        //     ['account_code' => $cash_account, 'debit' => $monthly_rent, 'credit' => 0],
        //     ['account_code' => 500, 'debit' => 0, 'credit' => $monthly_rent],
        // ];

        // createJournalEntry(
        //     $pdo,
        //     $payment_date,
        //     "Rent for Unit " . $_POST['unit_number'],
        //     $entries
        // );

        // $pdo->commit();

        // $_SESSION['success'] =
        //     "Single unit created successfully.";

        // header('Location: single_units.php');
        // exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();

        $_SESSION['error'] =
            'Failed to create the unit: ' . $e->getMessage();

        header('Location: single_units.php');
        exit;
    }
}
