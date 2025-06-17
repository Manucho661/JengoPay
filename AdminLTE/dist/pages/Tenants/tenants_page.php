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
    <div class="modal-content shadow-lg rounded">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color: #00192D; color: #FFC107;">
        <h5 class="modal-title">
          <i class="fas fa-tools mr-2"></i> Maintenance Request
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <form id="maintenanceRequestForm" enctype="multipart/form-data">
        <div class="modal-body" style="font-family: 'Segoe UI', sans-serif;">

          <div class="alert mb-4" style="background-color: #FFF3CD; color: #856404; border: 1px solid #FFE8A1;">
            <i class="fas fa-exclamation-circle mr-2"></i> Please fill out all fields carefully to avoid delays.
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label><strong><i class="fas fa-id-card mr-1 text-warning"></i> Request ID</strong></label>
              <input type="text" class="form-control bg-light" value="Auto-generated" disabled>
            </div>
            <div class="form-group col-md-6">
              <label for="request_date"><strong><i class="fas fa-calendar-alt mr-1 text-warning"></i> Request Date</strong></label>
              <input type="date" class="form-control" name="request_date" required style="color: black;">
            </div>
          </div>

          <div class="form-group">
            <label for="category"><strong><i class="fas fa-tag mr-1 text-warning"></i> Category</strong></label>
            <input type="text" class="form-control" name="category" placeholder="e.g. Electrical, Plumbing" required style="color: black;">
          </div>

          <div class="form-group">
            <label for="description"><strong><i class="fas fa-align-left mr-1 text-warning"></i> Description</strong></label>
            <textarea class="form-control" name="description" rows="3" placeholder="Describe the issue clearly..." required style="color: black;"></textarea>
          </div>

          <!-- Image Upload Section -->
          <div class="form-group">
            <label for="photo_upload"><strong><i class="fas fa-camera mr-1 text-warning"></i> Upload Photos</strong></label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="photo_upload" name="photos[]" multiple accept="image/*">
              <label class="custom-file-label" for="photo_upload">Choose image(s)...</label>
            </div>
            <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF</small>
          </div>

          <!-- Image Preview Section -->
          <div id="imagePreview" class="d-flex flex-wrap mt-3" style="gap: 10px;"></div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn" data-dismiss="modal" style="background-color: #00192D; color: #FFC107;">
            <i class="fas fa-times-circle mr-1"></i> Cancel
          </button>
          <button type="submit" class="btn font-weight-bold" style="background-color: #00192D; color: #FFC107;">
            <i class="fas fa-paper-plane mr-1"></i> Submit Request
          </button>
        </div>
      </form>

    </div>
  </div>
</div>



<!-- JS Includes -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS to Handle Maintenance Form -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('maintenanceRequestForm');
  const fileInput = document.getElementById('photo_upload');
  const preview = document.getElementById('imagePreview');

  // Handle form submission
  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch('submit_maintenance.php', {
        method: 'POST',
        body: formData
      });

      const text = await response.text(); // Get raw response
      console.log('Raw response:', text);

      let result;
      try {
        result = JSON.parse(text); // Try to parse JSON
      } catch (jsonError) {
        throw new Error('Invalid JSON: ' + jsonError.message);
      }

      if (result.success) {
        alert('✅Maintenance request submitted successfully!');
        form.reset();
        preview.innerHTML = ''; // Clear image previews
        $('#maintenanceModal').modal('hide');
      } else {
        alert('Error: ' + result.error);
      }

    } catch (err) {
      alert('Unexpected error. Please try again.');
      console.error('Fetch Error:', err);
    }
  });

  // Handle image preview
  fileInput.addEventListener('change', function (event) {
    preview.innerHTML = ''; // Clear previous previews

    Array.from(event.target.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.style.maxWidth = '100px';
        img.style.margin = '5px';
        img.style.borderRadius = '8px';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  });
});
</script>




</body>
</html>
