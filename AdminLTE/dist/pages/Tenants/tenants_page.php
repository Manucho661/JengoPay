<!-- tenants_page.php -->
<?php
// No form submission logic here – moved to submit_maintenance.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tenant Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- FontAwesome & Bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('building2.jpg');
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      height: 100vh;
    }
    .header-bar {
      background-color: #00192D;
      color: #FFFFFF;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header-bar .nav-links a {
      color: #FFC107;
      margin-left: 20px;
      text-decoration: none;
    }
    .header-bar .nav-links a:hover {
      color: #FF5722;
    }
  </style>
</head>

<body>
<div class="header-bar">
  <h1><i class="fas fa-users" style="color: #FFC107;"></i> Welcome Tenants</h1>
  <div class="nav-links">
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="units_page.php"><i class="fas fa-building"></i> Units</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</div>

<div class="container mt-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center">
    <!-- Maintenance Request Button (Left) -->
    <button class="btn mb-2 px-4 py-2 mx-auto" style="background-color: #00192D; color: #FFC107;" data-toggle="modal" data-target="#maintenanceModal">
      <i class="fas fa-tools"></i> Maintenance Requests
    </button>

    <!-- Rent Button (Center) -->
    <a href="../Tenants/Rent.php"><button class="btn mb-2 px-4 py-2 mx-auto" style="background-color: #00192D; color: #FFC107;" data-toggle="modal" data-target="#rentModal">
      <i class="fas fa-hand-holding-usd"></i> Rent
    </button></a>

    <!-- Vacate Button (Right) -->
    <button class="btn mb-2 px-4 py-2 mx-auto" style="background-color: #00192D; color: #FFC107;" data-toggle="modal" data-target="#vacateModal">
      <i class="fas fa-door-open"></i> Vacate
    </button>

    <!-- Shift Button (Right) -->
    <button class="btn mb-2 px-4 mx-auto" style="background-color: #00192D; color: #FFC107;" data-toggle="modal" data-target="#shiftModal">
      <i class="fas fa-sync-alt"></i> Shift
    </button>
  </div>
</div>



  <!-- Maintenance Request Modal -->
  <div class="modal fade" id="maintenanceModal" tabindex="-1" role="dialog" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
          <h5 class="modal-title"><i class="fas fa-tools"></i> Maintenance Request</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <form id="maintenanceRequestForm">
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Request ID</label>
                <input type="text" class="form-control" value="Auto-generated" disabled>
              </div>
              <div class="form-group col-md-6">
                <label for="request_date">Request Date</label>
                <input type="date" class="form-control" name="request_date" required style="color: black;">
              </div>
            </div>

            <div class="form-group">
              <label for="category">Category</label>
              <input type="text" class="form-control" name="category" required style="color: black;">
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" name="description" rows="3" required style="color: black;"></textarea>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="provider_id">Provider ID</label>
                <input type="text" class="form-control" name="provider_id" required style="color: black;">
              </div>
              <div class="form-group col-md-6">
                <label for="status">Status</label>
                <select class="form-control" name="status" required style="color: black;">
                  <option value="Pending"style="color: black;">Pending</option>
                  <option value="In Progress"style="color: black;">In Progress</option>
                  <option value="Completed" style="color: black;">Completed</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="payment_status">Payment Status</label>
                <select class="form-control" name="payment_status" required style="color: black;">
                  <option value="Unpaid" style="color: black;">Unpaid</option>
                  <option value="Paid" style="color: black;">Paid</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="payment_date">Payment Date</label>
                <input type="date" class="form-control" name="payment_date" style="color: black;">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #00192D; color: #FFC107;">Cancel</button>
            <button type="submit" class="btn" style="background-color: #00192D; color: #FFC107;">Submit Request</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<!-- JS Includes -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS to Handle Maintenance Form -->
<script>
document.getElementById('maintenanceRequestForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
    const response = await fetch('submit_maintenance.php', {
      method: 'POST',
      body: formData
    });

    const text = await response.text(); // First, read as plain text
    console.log('Raw response:', text);

    const result = JSON.parse(text); // Then try to parse as JSON

    if (result.success) {
      alert('Maintenance request submitted successfully!');
      form.reset();
      $('#maintenanceModal').modal('hide');
    } else {
      alert('Error: ' + result.error);
    }
  } catch (err) {
    alert('Unexpected error. Please try again.');
    console.error('Fetch Error:', err);
  }
});
</script>


</body>
</html>
