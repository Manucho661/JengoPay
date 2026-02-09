<?php
include_once 'processes/encrypt_decrypt_function.php';
if (isset($_GET['add_bed_sitter']) && !empty($_GET['add_bed_sitter'])) {
    $id = $_GET['add_bed_sitter'];
    $id = encryptor('decrypt', $id);
    $_SESSION['building_id'] = $id; // persist building id across different requests
    try {
        if (!empty($id)) {
            $select = "SELECT * FROM buildings WHERE id =:id";
            $stmt = $pdo->prepare($select);
            $stmt->execute(array(':id' => $id));

            while ($row = $stmt->fetch()) {
                $building_name = $row['building_name'];
                $county = $row['county'];
                $constituency = $row['constituency'];
                $ward = $row['ward'];
                $structure_type = $row['structure_type'];
                $floors_no = $row['floors_no'];
                $no_of_units = $row['no_of_units'];
                $building_type = $row['building_type'];
                $tax_rate = $row['tax_rate'];
                $ownership_info = $row['ownership_info'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $id_number = $row['id_number'];
                $primary_contact = $row['primary_contact'];
                $other_contact = $row['other_contact'];
                $owner_email = $row['owner_email'];
                $postal_address = $row['postal_address'];
                $entity_name = $row['entity_name'];
                $entity_phone = $row['entity_phone'];
                $entity_phoneother = $row['entity_phoneother'];
                $entity_email = $row['entity_email'];
                $entity_rep = $row['entity_rep'];
                $rep_role = $row['rep_role'];
                $entity_postal = $row['entity_postal'];
                $ownership_proof = $row['ownership_proof'];
                $title_deed = $row['title_deed'];
                $legal_document = $row['legal_document'];
                $utilities = $row['utilities'];
                $photo_one = $row['photo_one'];
                $photo_two = $row['photo_two'];
                $photo_three = $row['photo_three'];
                $photo_four = $row['photo_four'];
                $added_on = $row['added_on'];
                $ownership_proof = $row['ownership_proof'];
                $title_deed = $row['title_deed'];
                $legal_document = $row['legal_document'];
                $photo_one = $row['photo_one'];
                $photo_two = $row['photo_two'];
                $photo_three = $row['photo_three'];
                $photo_four = $row['photo_four'];
            }
        } else {
            echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'No Information!',
                                  text: 'No Building Information could be Extracted from the Database',
                                  confirmButtonColor: '#cc0001'
                                  });
                                  </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to Load Building Information. " . addslashes($e->getMessage()) . "',
                                    confirmButtonColor: '#cc0001'
                                    });
                                    </script>";
    }
}

//if the Submit button is clicked
if (isset($_POST['submit_unit'])) {
    //Check for duplicate unit_number + building_link and avoid double entry of information
    try {
        $buildingId = $_SESSION['building_id'] ?? null;

        if (!$buildingId) {
            throw new Exception('Invalid building context.');
        }
        $check = $pdo->prepare("SELECT COUNT(*) FROM bedsitter_units WHERE unit_number = :unit_number AND building_link = :building_link");
        $check->execute([
            ':unit_number'   => $_POST['unit_number'],
            ':building_link' => $_POST['building_link']
        ]);

        if ($check->fetchColumn() > 0) {
            // Check if Duplicate found
            echo "
                                            <script>
                                                Swal.fire({
                                                    title: 'Warning!',
                                                    text: 'No double submission of data: this unit already exists in the Database.',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    window.history.back();
                                                });
                                            </script>";
            exit;
        }
        // Start transaction
        $pdo->beginTransaction();

        // --------------------------------------------------
        // Get unit_category_id (bed_sitter_unit)
        // --------------------------------------------------
        $sql = "
                                        SELECT id 
                                        FROM unit_categories 
                                        WHERE category_name = :category_name 
                                        LIMIT 1
                                    ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':category_name' => 'bed_sitter_unit'
        ]);

        $bedSitterUnitCategoryId = $stmt->fetchColumn();

        // Safety check
        if ($bedSitterUnitCategoryId === false) {
            throw new Exception('Unit category "bed_sitter_unit" not found.');
        }

        //Insert into Bed Sitter Units
        // $stmt = $pdo->prepare("INSERT INTO bedsitter_units (structure_type, first_name, last_name, owner_email, entity_name, entity_phone, entity_phoneother, entity_email, unit_number, purpose, building_link, location, water_meter, monthly_rent, occupancy_status, created_at) VALUES (:structure_type, :first_name, :last_name, :owner_email, :entity_name, :entity_phone, :entity_phoneother, :entity_email, :unit_number, :purpose, :building_link, :location, :water_meter, :monthly_rent, :occupancy_status, NOW())");
        $stmt = $pdo->prepare("INSERT INTO building_units (landlord_id, building_id, unit_category_id, unit_number, purpose, location, water_meter, monthly_rent, occupancy_status, created_at) VALUES (:landlord_id, :building_id, :unit_category_id, :unit_number, :purpose, :location, :water_meter, :monthly_rent, :occupancy_status, NOW())");

        $stmt->execute([
            // ':structure_type' => $_POST['structure_type'],
            // ':first_name' => $_POST['first_name'],
            // ':last_name' => $_POST['last_name'],
            // ':owner_email' => $_POST['owner_email'],
            // ':entity_name' => $_POST['entity_name'],
            // ':entity_phone' => $_POST['entity_phone'],
            // ':entity_phoneother' => $_POST['entity_phoneother'],
            // ':entity_email' => $_POST['entity_email'],
            ':landlord_id' => $landlord_id,
            ':building_id'      => $buildingId,
            ':unit_category_id' => $bedSitterUnitCategoryId,
            ':unit_number' => $_POST['unit_number'],
            ':purpose' => $_POST['purpose'],
            ':location' => $_POST['location'],
            ':water_meter' => $_POST['water_meter'],
            ':monthly_rent' => $_POST['monthly_rent'],
            ':occupancy_status' => $_POST['occupancy_status']
        ]);
        
        // Get inserted unit_id from bedsitter units. This will be used to initiate recurring bills on the foreign key unit_id
        $unit_id = $pdo->lastInsertId();

        //Insert the Bills of the Unit into bedsitter bills
        if (!empty($_POST['bill'])) {
            $stmtBill = $pdo->prepare("INSERT INTO bed_seater_bills (unit_id, bill, qty, unit_price, subtotal, created_at) VALUES (:unit_id, :bill, :qty, :unit_price, :subtotal, NOW())");

            foreach ($_POST['bill'] as $i => $billName) {
                if ($billName != "") {
                    $qty       = $_POST['qty'][$i] ?? 0;
                    $unitPrice = $_POST['unit_price'][$i] ?? 0;
                    $subtotal  = $qty * $unitPrice;

                    $stmtBill->execute([
                        ':unit_id'    => $unit_id,
                        ':bill'       => $billName,   // ✅ match placeholder
                        ':qty'        => $qty,
                        ':unit_price' => $unitPrice,
                        ':subtotal'   => $subtotal
                    ]);
                }
            }
        }
        $pdo->commit();

        header("Location: bed_sitter_units.php?success=1");

        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "Error: " . $e->getMessage();
        // SweetAlert error
        echo "
                                        <script>
                                            Swal.fire({
                                                title: 'Error!',
                                                text: '" . addslashes($e->getMessage()) . "',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            });
                                        </script>";
        exit;
    }
}
