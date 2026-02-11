<?php
if (isset($_POST['submit_building'])) {
    $tm = md5(time()); // Unique prefix for uploaded files

    // ---------- File Upload Handling ----------
    function uploadFile($fileKey, $tm)
    {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $name = basename($_FILES[$fileKey]['name']);
            $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $name); // sanitize

            // Set the upload directory
            $uploadDir = __DIR__ . "/all_uploads/";

            // Create the folder if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Full destination path
            $destination = $uploadDir . $tm . "_" . $safeName;

            // Move the uploaded file
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $destination)) {
                return $destination; // returns full path
            } else {
                return null; // failed to move file
            }
        }

        return null; // file not uploaded
    }

    $ownership_proof_destination = uploadFile('ownership_proof', $tm);
    $title_deed_destination      = uploadFile('title_deed', $tm);
    $legal_document_destination  = uploadFile('legal_document', $tm);
    $photo_one_destination       = uploadFile('photo_one', $tm);
    $photo_two_destination       = uploadFile('photo_two', $tm);
    $photo_three_destination     = uploadFile('photo_three', $tm);
    $photo_four_destination      = uploadFile('photo_four', $tm);

    // ---------- Input Fields ----------
    $building_name   = $_POST['building_name'] ?? null;
    $county          = $_POST['county'] ?? null;
    $constituency    = $_POST['constituency'] ?? null;
    $ward            = $_POST['ward'] ?? null;
    $structure_type  = $_POST['structure_type'] ?? null;
    $floors_no       = $_POST['floors_no'] ?? null;
    $no_of_units     = $_POST['no_of_units'] ?? null;
    $building_type   = $_POST['category'] ?? null;
    $tax_rate        = $_POST['tax_rate'] ?? null;
    $ownership_mode  = $_POST['ownership_info'] ?? null;
    $first_name      = $_POST['first_name'] ?? null;
    $last_name       = $_POST['last_name'] ?? null;
    $id_number       = $_POST['id_number'] ?? null;
    $primary_contact = $_POST['primary_contact'] ?? null;
    $other_contact   = $_POST['other_contact'] ?? null;
    $owner_email     = $_POST['owner_email'] ?? null;
    $postal_address  = $_POST['postal_address'] ?? null;
    $entity_name     = $_POST['entity_name'] ?? null;
    $entity_phone    = $_POST['entity_phone'] ?? null;
    $entity_phoneother = $_POST['entity_phoneother'] ?? null;
    $entity_email    = $_POST['entity_email'] ?? null;
    $entity_rep      = $_POST['entity_rep'] ?? null;
    $rep_role        = $_POST['rep_role'] ?? null;
    $entity_postal   = $_POST['entity_postal'] ?? null;
    $confirm         = $_POST['confirm'] ?? 0;

    // ---------- Amenities ----------
    $amenities = $_POST['amenities'] ?? [];
    $amenities_json = json_encode($amenities, JSON_UNESCAPED_UNICODE);

    try {

        //landlord id
        $userId = $_SESSION['user']['id'];
        $stmt = $pdo->prepare("SELECT id FROM landlords WHERE user_id = ?");
        $stmt->execute([$userId]);
        $landlord = $stmt->fetch();
        $landlord_id = $landlord['id'];

        $sql = "INSERT INTO buildings (
    landlord_id, building_name, county, constituency, ward, structure_type, floors_no, no_of_units, category, 
    tax_rate, ownership_mode, first_name, last_name, id_number, primary_contact, other_contact, owner_email, 
    postal_address, entity_name, entity_phone, entity_phoneother, entity_email, entity_rep, rep_role, entity_postal, 
    ownership_proof, title_deed, legal_document, utilities, photo_one, photo_two, photo_three, photo_four, confirm
) VALUES (
    :landlord_id, :building_name, :county, :constituency, :ward, :structure_type, :floors_no, :no_of_units, :category, 
    :tax_rate, :ownership_mode, :first_name, :last_name, :id_number, :primary_contact, :other_contact, :owner_email, 
    :postal_address, :entity_name, :entity_phone, :entity_phoneother, :entity_email, :entity_rep, :rep_role, :entity_postal, 
    :ownership_proof, :title_deed, :legal_document, :utilities, :photo_one, :photo_two, :photo_three, :photo_four, :confirm
)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':landlord_id' => $landlord_id,
            ':building_name'   => $building_name,
            ':county'          => $county,
            ':constituency'    => $constituency,
            ':ward'            => $ward,
            ':structure_type'  => $structure_type,
            ':floors_no'       => $floors_no,
            ':no_of_units'     => $no_of_units,
            ':category'        => $building_type, // Correct the variable name here
            ':tax_rate'        => $tax_rate,
            ':ownership_mode'  => $ownership_mode, // Ensure the variable matches
            ':first_name'      => $first_name,
            ':last_name'       => $last_name,
            ':id_number'       => $id_number,
            ':primary_contact' => $primary_contact,
            ':other_contact'   => $other_contact,
            ':owner_email'     => $owner_email,
            ':postal_address'  => $postal_address,
            ':entity_name'     => $entity_name,
            ':entity_phone'    => $entity_phone,
            ':entity_phoneother' => $entity_phoneother,
            ':entity_email'    => $entity_email,
            ':entity_rep'      => $entity_rep,
            ':rep_role'        => $rep_role,
            ':entity_postal'   => $entity_postal,
            ':ownership_proof' => $ownership_proof_destination,
            ':title_deed'      => $title_deed_destination,
            ':legal_document'  => $legal_document_destination,
            ':utilities'       => $amenities_json,
            ':photo_one'       => $photo_one_destination,
            ':photo_two'       => $photo_two_destination,
            ':photo_three'     => $photo_three_destination,
            ':photo_four'      => $photo_four_destination,
            ':confirm'         => $confirm,
        ]);

        $building_id = $pdo->lastInsertId();

        $_SESSION['success'] = "Building created successfully.";


        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] =
            'Failed to create building: ' . $e->getMessage();

        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}
