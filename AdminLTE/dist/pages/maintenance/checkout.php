<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .app-wrapper {
        display: grid;
        grid-template-columns: 250px 1fr;
        grid-template-rows: 60px 1fr;
        grid-template-areas:
            "header header"
            "sidebar main";
            max-width: 100vw;
            min-height: 100vh;
        }
        .sidebar {
            background-color: #00192D;
            color: white;
            flex-shrink: 0;
            padding-top: 1rem;
            grid-area: sidebar;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            transition: background-color 0.2s;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #495057;
        }
        .sidebar .submenu a {
            padding-left: 40px;
            font-size: 0.95rem;
        }
        main{
        background-color:rgba(128,128,128, 0.1);
        padding: 5px;
        grid-area: main;
        }
       .menu-header {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: bold;
    margin-top: 10px;
  }

  .arrow {
    transition: transform 0.3s ease;
  }

  .submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease;
    padding-left: 1.5rem;
    display: flex;
    flex-direction: column;
  }

  .submenu a {
    padding: 4px 0;
    text-decoration: none;
  }

  .menu-group.open .submenu {
    max-height: 500px;
  }

  .menu-group.open .arrow {
    transform: rotate(90deg);
  }
    

    </style>
</head>
<body>
    <div class="app-wrapper">

        <?php
        include "../includes/sidebar1.php"
        ?>
    
        <main id="main" class="main">
            <!-- Payment Modal -->
            <div class="modal fade" id="payProviderModal" tabindex="-1" aria-labelledby="payProviderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="payProviderModalLabel">Pay Provider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body with Step Content -->
                <div class="modal-body">

                    <!-- Step 1: Show Provider Details -->
                    <div id="step-1">
                    <p><strong>Provider Name:</strong> <span id="providerName">John Doe Ltd</span></p>
                    <p><strong>Work Done:</strong> <span id="workDescription">Fixed leaking roof in Block A</span></p>
                    <p><strong>Amount:</strong> <span id="paymentAmount">KES 8,500</span></p>
                    <button class="btn btn-primary" id="nextStepBtn">Proceed to Payment</button>
                    </div>

                    <!-- Step 2: Choose Payment Method -->
                    <div id="step-2" class="d-none">
                    <form id="paymentForm">
                        <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Select Payment Method</label>
                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                            <option value="">-- Choose --</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                        </div>

                        <div id="mpesaPhoneSection" class="d-none">
                        <label for="phoneNumber" class="form-label">M-Pesa Phone Number</label>
                        <input type="tel" class="form-control" name="phone" id="phoneNumber" placeholder="07XXXXXXXX" required>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Pay Now</button>
                    </form>
                    </div>

                </div>

                </div>
            </div>
            </div>

        </main>

    </div>

    <script>
  function toggleMenu(element) {
    const group = element.parentElement;
    group.classList.toggle('open');
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
document.getElementById('nextStepBtn').addEventListener('click', function () {
  document.getElementById('step-1').classList.add('d-none');
  document.getElementById('step-2').classList.remove('d-none');
});

document.getElementById('paymentMethod').addEventListener('change', function () {
  const mpesaSection = document.getElementById('mpesaPhoneSection');
  if (this.value === 'mpesa') {
    mpesaSection.classList.remove('d-none');
  } else {
    mpesaSection.classList.add('d-none');
  }
});

// Submit Handler (replace with your real AJAX or form post)
document.getElementById('paymentForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const method = document.getElementById('paymentMethod').value;

  if (method === 'mpesa') {
    const phone = document.getElementById('phoneNumber').value;
    // Call your backend endpoint to trigger M-Pesa STK Push
    alert(`Sending M-Pesa request to ${phone}`);
  } else {
    alert('Proceeding with Bank Transfer');
  }

  // Optionally close modal after
  const modal = bootstrap.Modal.getInstance(document.getElementById('payProviderModal'));
  modal.hide();
});
</script>

</body>
</html>