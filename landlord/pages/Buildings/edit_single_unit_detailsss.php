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
                    if(isset($_GET['edit']) && !empty($_GET['edit'])) {
                        $unit_id = $_GET['edit'];
                        $unit_id = encryptor('decrypt', $unit_id);

                    try{
                        // 1. Fetch unit details
                        $stmt = $conn->prepare("SELECT * FROM single_units WHERE id = ?");
                        $stmt->execute([$unit_id]);
                        $unit = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$unit) {
                            die("Unit not found!");
                        }

                        // 2. Fetch bills (always return array)
                        $stmtBills = $conn->prepare("SELECT * FROM single_unit_bills WHERE unit_id = ?");
                        $stmtBills->execute([$unit_id]);
                        $bills = $stmtBills->fetchAll(PDO::FETCH_ASSOC) ?: [];
                        }catch(PDOException $e){  
                            //if the query fails to select
                        }
                    }

                    //If the Update Button is Clicked
                    if(isset($_POST['update'])) {
                        $id               = $_POST['id'];
                        $unit_number      = $_POST['unit_number'];
                        $purpose          = $_POST['purpose'];
                        $building_link    = $_POST['building_link'];
                        $location         = $_POST['location'];
                        $monthly_rent     = $_POST['monthly_rent'];
                        $occupancy_status = $_POST['occupancy_status'];
                        try{
                            //No Update to be done until Any of the Form values are changed
                            $no_changes = "SELECT * FROM single_units WHERE unit_number = '$_POST[unit_number]' AND purpose = '$_POST[purpose]' AND building_link = '$_POST[building_link]' AND location = '$_POST[location]' AND monthly_rent = '$_POST[monthly_rent]' AND occupancy_status = '$_POST[occupancy_status]'";
                            $stmt = $conn->prepare($no_changes);
                            $stmt->execute();
                            if($stmt->rowCount() > 0) {
                                ?>
                                    <script>
                                      alert('Update Failed! You Haven\'t Changed Anything in the Form Fields');
                                      window.location.href = "single_units.php";
                                    </script>
                                <?php
                            } else {
                                // Start transaction
                                if (!$conn->inTransaction()) {
                                    $conn->beginTransaction();
                                }

                                // 1. Update main unit details
                                $stmt = $conn->prepare("
                                    UPDATE single_units 
                                    SET unit_number = ?, 
                                        purpose = ?, 
                                        building_link = ?, 
                                        location = ?, 
                                        monthly_rent = ?, 
                                        occupancy_status = ?
                                    WHERE id = ?
                                ");

                                $stmt->execute([
                                    $unit_number,
                                    $purpose,
                                    $building_link,
                                    $location,
                                    $monthly_rent,
                                    $occupancy_status,
                                    $id
                                ]);

                                // 2. Delete old bills
                                $stmt = $conn->prepare("DELETE FROM single_unit_bills WHERE unit_id = ?");
                                $stmt->execute([$id]);

                                // 3. Insert updated bills
                                if (!empty($_POST['bill'])) {
                                    $stmt = $conn->prepare("
                                        INSERT INTO single_unit_bills (unit_id, bill, qty, unit_price) 
                                        VALUES (?, ?, ?, ?)
                                    ");
                                    foreach ($_POST['bill'] as $i => $billName) {
                                        $bill       = trim($billName);
                                        $qty        = !empty($_POST['qty'][$i]) ? $_POST['qty'][$i] : 0;
                                        $unit_price = !empty($_POST['unit_price'][$i]) ? $_POST['unit_price'][$i] : 0;

                                        if ($bill !== "") { // avoid inserting empty rows
                                            $stmt->execute([$id, $bill, $qty, $unit_price]);
                                        }
                                    }
                                }

                                // Commit changes
                                if ($conn->inTransaction()) {
                                    $conn->commit();
                                }

                                // ✅ Success feedback
                                echo '<div id="countdown" class="alert alert-success" role="alert"></div>
                                        <script>
                                          var timeleft = 10;
                                          var downloadTimer = setInterval(function(){
                                          if(timeleft <= 0){
                                            clearInterval(downloadTimer);
                                            
                                          } else {
                                              document.getElementById("countdown").innerHTML = "Bed Sitter Unit Updated Succeessfully! Redirecting in " + timeleft + " seconds remaining";
                                              window.location.href = "single_units.php";
                                          }
                                            timeleft -= 1;
                                            }, 1000);
                                        </script>';
                            }

                        } catch(Exception $e){
                            if ($conn->inTransaction()) {
                                $conn->rollBack();
                            }
                            echo "<div class='alert alert-danger'>Error updating record: " . $e->getMessage() . "</div>";
                        }
                    } 
                ?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">
            <!-- Unit Details -->
            <input type="hidden" value="<?= htmlspecialchars($unit['id']); ?>" name="id">
            <div class="card shadow" style="border: 1px solid rgb(0,25,45,.3);">
              <div class="card-header" style="background-color: rgb(0, 25,45); color:#fff;"><i class="bi bi-house-add"></i> Unit Details</div>
              <div class="card-body">
                <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit Number</label>
                            <input type="text" name="unit_number" class="form-control" value="<?= htmlspecialchars($unit['unit_number']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Purpose</label>
                            <input type="text" name="purpose" class="form-control" value="<?= htmlspecialchars($unit['purpose']); ?>">
                        </div>
                    </div>
                </div> <hr>
                <div class="row p-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Building Link</label>
                            <input type="text" name="building_link" class="form-control" value="<?= htmlspecialchars($unit['building_link']); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($unit['location']); ?>">
                        </div>
                    </div>
                </div> <hr>
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Monthly Rent</label>
                                <input type="number" step="0.01" name="monthly_rent" class="form-control" value="<?= htmlspecialchars($unit['monthly_rent']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Occupancy Status</label>
                                <select name="occupancy_status" class="form-control">
                                    <option value="<?= htmlspecialchars($unit['occupancy_status']); ?>" selected hidden><?= htmlspecialchars($unit['occupancy_status']); ?></option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Vacant">Vacant</option>
                                    <option value="Under Maintenance">Under Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
              </div>
            </div>
            <!-- Recurring Expenses -->
            <div class="card shadow" style="border: 1px solid rgb(0,25,45,.3);">
              <div class="card-header" style="background-color: rgb(0, 25,45); color:#fff;"><i class="bi bi-wallet"></i> Recuring Expenses</div>
              <div class="card-body">
                                <table class="table table-bordered" id="billsTable">
                    <thead>
                        <tr>
                            <th>Bill</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($bills)): ?>
                        <?php foreach ($bills as $bill): ?>
                        <tr>
                            <td><input type="text" name="bill[]" class="form-control bill" value="<?= htmlspecialchars($bill['bill']); ?>"></td>
                            <td><input type="number" name="qty[]" class="form-control qty" value="<?= htmlspecialchars($bill['qty']); ?>"></td>
                            <td><input type="number" step="0.01" name="unit_price[]" class="form-control unit_price" value="<?= htmlspecialchars($bill['unit_price']); ?>"></td>
                            <td><input type="text" class="form-control subtotal" value="<?= $bill['qty'] * $bill['unit_price']; ?>" readonly></td>
                            <td><button type="button" class="btn btn-sm removeRow shadow" style="background-color:#cc0001; color: #fff; font-weight: bold;">X</button></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <!-- Start with an empty row if no bills exist -->
                    <tr>
                        <td><input type="text" name="bill[]" class="form-control bill"></td>
                        <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
                        <td><input type="number" step="0.01" name="unit_price[]" class="form-control unit_price"></td>
                        <td><input type="text" class="form-control subtotal" readonly></td>
                        <td><button type="button" class="btn btn-sm removeRow" style="background-color:#cc0001; color: #fff;"><i class="bi bi-trash"></i> </button></td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-sm shadow mt-2" style="border: 1px solid #00192D; color: #00192D;" id="addRow"><i class="bi bi-plus"></i> Add More</button>
              </div>
            </div>
            <div class="card shadow">
              <div class="card-body text-right">
                <button class="btn btn-sm shadow" type="submit" name="update" style="border: 1px solid #00192D; color: #00192D;"><i class="bi bi-check"></i> Update and Submit</button>
              </div>
            </div>
          </form>
        </div>
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
  <?php include_once 'includes/required_scripts.php';?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const tableBody = document.querySelector("#billsTable tbody");

    // Add new row
    document.getElementById("addRow").addEventListener("click", function() {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><input type="text" name="bill[]" class="form-control bill"></td>
            <td><input type="number" name="qty[]" class="form-control qty" value="1"></td>
            <td><input type="number" step="0.01" name="unit_price[]" class="form-control unit_price"></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-sm shadow removeRow" style="background-color:#cc0001; color:#fff; font-weight:bold;">X</button></td>`;
        tableBody.appendChild(row);
    });

    // Delegate remove buttons
    tableBody.addEventListener("click", function(e) {
        if (e.target.classList.contains("removeRow")) {
            e.target.closest("tr").remove();
        }
    });

    // Auto-calc subtotal
    tableBody.addEventListener("input", function(e) {
        if (e.target.classList.contains("qty") || e.target.classList.contains("unit_price")) {
            const row = e.target.closest("tr");
            const qty = parseFloat(row.querySelector(".qty").value) || 0;
            const price = parseFloat(row.querySelector(".unit_price").value) || 0;
            row.querySelector(".subtotal").value = (qty * price).toFixed(2);
        }
    });
});
</script>
</body>
</html>