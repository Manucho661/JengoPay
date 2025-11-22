<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php';?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include_once 'includes/nav_bar.php';?>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <?php include_once 'includes/side_menus.php';?>
    <!-- Main Sidebar Container -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <?php include_once 'includes/dashboard_bradcrumbs.php';?>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <?php
            include_once 'processes/encrypt_decrypt_function.php';
            // Initialize $tenant_info to prevent errors if 'invoice' GET param is not set
            $tenant_info = [
                'tfirst_name' => '', 'tmiddle_name' => '', 'tlast_name' => '',
                'tmain_contact' => '', 'talt_contact' => '', 'temail' => '',
                'monthly_rent' => 0, 'final_bill' => 0
            ];
            $monthly_rent = 0;
            $final_bill = 0;

            // Fetch Tenant Information from the Database
            if(isset($_GET['invoice']) && !empty($_GET['invoice'])) {
                $id = $_GET['invoice'];
                $decrypted_id = encryptor('decrypt', $id); // Assuming encryptor returns null/false on error

                if ($decrypted_id !== null && $decrypted_id !== false) {
                    try {
                        $tenant = $conn->prepare("SELECT * FROM single_units WHERE id = ? ");
                        $tenant->execute([$decrypted_id]); // Use decrypted_id here
                        $tenant_info = $tenant->fetch(PDO::FETCH_ASSOC);

                        if(!$tenant_info) {
                        // Use SweetAlert for feedback if no data found
                        echo "<script>
                                  Swal.fire({
                                    icon: 'warning',
                                    title: 'No Data',
                                    text: 'No Active Tenant Data Found for the provided ID.'
                                  });
                            </script>";
                        // Optionally, redirect or set default empty values again
                        $tenant_info = [ /* ... empty defaults ... */ ];
                        } else {
                            $monthly_rent = $tenant_info['monthly_rent'] ?? 0;
                            $final_bill = $tenant_info['final_bill'] ?? 0; // Ensure it's not null
                        }
                    } catch (PDOException $e) {
                        // Log the database error
                        error_log("Database error fetching tenant info: " . $e->getMessage());
                        echo "<script>
                            Swal.fire({
                              icon: 'error',
                              title: 'Database Error',
                              text: 'Could not fetch tenant data. Please try again.'
                            });
                        </script>";
                    $tenant_info = [ /* ... empty defaults ... */ ]; // Reset to avoid undefined variable errors
                    }
                } else {
                  echo "<script>
                            Swal.fire({
                              icon: 'error',
                              title: 'Invalid ID',
                              text: 'The provided tenant ID is invalid.'
                            });
                        </script>";
                  $tenant_info = [ /* ... empty defaults ... */ ];
              }
            }
          ?>
          <?php
            include_once 'includes/tenant_invoice_form.php';
          ?>
      </section>
      <!-- /.content -->

      <!-- Help Pop Up Form -->
      <?php include_once 'includes/lower_right_popup_form.php' ;?>
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <?php include_once 'includes/footer.php';?>

  </div>
  <!-- ./wrapper -->
  <!-- Required Scripts -->
  <?php
    include_once 'includes/required_scripts.php';

    #Handle Invoice Submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validate essential values
        // ------------------------------------------------------
        if (empty($_POST['invoice_date'])) {
            ?>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Data!',
                    text: 'Please Fill Invoice Date.'
                });
                </script>
            <?php
        }

        if (empty($_POST['invoice_items'])) {
            ?>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Data!',
                    text: 'Invoice Items Missing. Fill Up.'
                });
                </script>
            <?php
        }

        // Decode items JSON
        $items = json_decode($_POST['invoice_items'], true);
        if (!is_array($items) || count($items) === 0) {
            throw new Exception("Invalid invoice items data.");
        }

        // ------------------------------------------------------
        // Handle File Upload (if any)
        // ------------------------------------------------------
        $attachmentName = NULL;
        if (!empty($_FILES["attachment"]["name"])) {
            $allowedExt = ["pdf", "jpg", "jpeg", "png", "docx"];
            $fileName = $_FILES["attachment"]["name"];
            $fileTmp = $_FILES["attachment"]["tmp_name"];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                throw new Exception("Invalid attachment type.");
            }

            // Generate safe file name
            $attachmentName = uniqid("file_") . "." . $ext;
            $uploadPath = "uploads/invoices/" . $attachmentName;

            // Ensure folder exists
            if (!is_dir("all_uploads")) {
                mkdir("all_uploads", 0777, true);
            }

            if (!move_uploaded_file($fileTmp, $uploadPath)) {
                throw new Exception("Failed to upload attachment.");
            }
        }

        // ------------------------------------------------------
        // Begin transaction
        // ------------------------------------------------------
        $conn->beginTransaction();

        // ------------------------------------------------------
        // Insert into single_units_invoice
        // ------------------------------------------------------

        try {
            $sql = "INSERT INTO single_units_invoice (invoice_no, receiver, main_contact, alt_contact, email, invoice_date, due_date, payment_status, notes, subtotal, total_tax, final_total, attachment) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $_POST['invoice_no'],
                $_POST['receiver'],
                $_POST['main_contact'] ?? null,
                $_POST['alt_contact'] ?? null,
                $_POST['email'] ?? null,
                $_POST['invoice_date'],
                $_POST['due_date'],
                $_POST['paymentStatus'] ?? "Pending",
                $_POST['notes'] ?? null,
                $_POST['subtotalValue'],
                $_POST['totalTaxValue'],
                $_POST['finalTotalValue'],
                $attachmentName
            ]);
            // Get the invoice ID
        $invoice_id = $conn->lastInsertId();
        // ------------------------------------------------------
        // Insert the invoice items
        // ------------------------------------------------------
        $sqlItem = "INSERT INTO single_units_invoice_items (invoice_id, item_name, description, unit_price, quantity, tax_type, tax_amount, total_price) VALUES (?,?,?,?,?,?,?,?)";
        $stmtItem = $conn->prepare($sqlItem);

        foreach ($items as $item) {
            // Prevent empty lines/objects
            if (empty($item["item_name"])) continue;

            $stmtItem->execute([
                $invoice_id,
                $item["item_name"],
                $item["description"] ?? "",
                $item["unit_price"],
                $item["quantity"],
                $item["tax_type"],
                $item["tax_amount"],
                $item["total_price"]
            ]);
        }
        echo "
            <script>
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Invoice Submitted Successfully.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                              window.location.href = 'single_unit_invoices.php';
                            }
                        });
                    }, 800); // short delay to smooth transition from loader
            </script>";
        // ------------------------------------------------------
        // Commit Transaction
        // ------------------------------------------------------
        $conn->commit();
        } catch (Exception $e) {
            // Rollback on any error
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            echo "<script>
                  Swal.fire({
                  icon: 'error',
                  title: 'Error Saving Tenant',
                  text: '" . addslashes($e->getMessage()) . "',
                  confirmButtonColor: '#cc0001'
                  });
              </script>";
        }
    }
  ?>
</body>

</html>