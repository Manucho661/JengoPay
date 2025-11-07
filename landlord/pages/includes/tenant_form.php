<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="id" value="<?= htmlspecialchars($id) ;?>">
  <input type="hidden" name="occupancy_status" value="Occupied">
  <!-- Tenant Personal Information -->
  <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
    <div class="card-header" style="background-color: #00192D; color:#fff;">Personal Information</div>
    <div class="card-body">
      <!-- Name Details -->
       <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="tfirst_name" required class="form-control" placeholder="First Name">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="tmiddle_name" name="tmiddle_name" required class="form-control" placeholder="Middle Name">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="tlast_name" required class="form-control" placeholder="Last Name">
          </div>
        </div>
      </div>
      <!-- Contact Details -->
       <div class="row">
        <div class="col-md-3">
          <div class="for-group">
            <label for="main_contact" class="form-label">Main WhatsApp Contact</label>
            <input id="main_contact" type="tel" name="tmain_contact" class="form-control" placeholder="Enter phone number" required>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="alt_contact" class="form-label">Alternative Contact</label>
            <input id="alt_contact" type="tel" name="talt_contact" class="form-control" placeholder="Alternative phone number" required>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-group">
              <input type="email" id="email" name="temail" required class="form-control" placeholder="Email">
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <!-- ID Mode Radio Buttons -->
           <div class="form-group mb-3">
            <label><b>Select Mode of Identification:</b></label>
            <div class="row">
              <div class="col-md-6">
                <input type="radio" name="idMode" value="national" id="radioNational" required>
                <label for="radioNational">National ID</label>
              </div>
              <div class="col-md-6">
                <input type="radio" name="idMode" value="passport" id="radioPassport" class="ms-3" required>
                <label for="radioPassport">Passport</label>
              </div>
            </div>
          </div>
          <!-- National ID Section -->
           <div id="nationalIdSection" style="display:none; margin-top:10px;">
            <label for="nationalId">National ID Number:</label>
            <input type="number" id="nationalId" class="form-control shadow" placeholder="ID Number" name="id_no" pattern="[0-9]{6,10}"> <button type="button" onclick="checkNationalId()" class="btn btn-sm btn-outline-dark mt-2">OK</button>
          </div>
          <!-- Passport Section -->
           <div id="passportPopup" style="display:none; margin-top:10px;">
            <label for="passportNumber">Passport Number:</label>
            <input type="text" id="passportNumber" class="form-control shadow" placeholder="Passport Number" name="pass_no" pattern="[A-Z0-9]{5,15}">
            <button type="button" onclick="checkPassport()" class="btn btn-sm btn-outline-danger mt-2">OK</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Leasing Information -->
   <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
    <div class="card-header" style="background-color: #00192D; color:#fff;">Leasing Information</div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="leasingPeriod">Leasing Period (In Months)</label>
            <input type="number" id="leasingPeriod" required class="form-control" name="leasing_period">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="leasingStart">Leasing Starts On</label>
            <input type="date" id="leasingStart" required class="form-control" name="leasing_start_date">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="leasingEnd">Leasing Ends On</label>
            <input type="date" id="leasingEnd" readonly class="form-control" name="leasing_end_date">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <div class="form-group">
            <label for="moveIn">Move In Date</label>
            <input type="date" id="moveIn" required class="form-control" name="move_in_date">
          </div>
          <div class="form-group">
            <label for="moveOut">Move Out Date</label>
            <input type="date" id="moveOut" readonly class="form-control" name="move_out_date">
          </div>
          <div class="form-group">
            <label for="account_no">Unit Number</label>
            <input type="text" id="account_no" name="account_no" required class="form-control" value="<?= htmlspecialchars($unit_number); ?>" readonly>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
  </div>
  <!-- Uploads Information -->
   <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
    <div class="card-header" style="background-color: #00192D; color:#fff;">Uploads</div>
    <div class="card-body">
      <div class="form-group">
        <label for="id_upload">Identification Upload</label>
        <input type="file" id="id_upload" required name="id_upload" class="form-control" accept=".jpg,.jpeg,.png,.pdf" onchange="previewIdUpload(this)">
      </div>
      <div id="idPreview" style="margin-top:10px; display:none;"></div>
      <div class="form-group">
        <label for="tax_pin_copy">TAX PIN Upload</label>
        <input type="file" id="tax_pin_copy" name="tax_pin_copy" required class="form-control" accept=".jpg,.jpeg,.png,.pdf" onchange="previewTaxPinCopy(this)">
      </div>
      <div id="taxPinPreview" style="margin-top:10px; display:none;"></div>
      <div class="form-group">
        <label for="rental_agreement">Rental Agreement Upload</label>
        <input type="file" id="rental_agreement" required name="rental_agreement" class="form-control" accept="application/pdf" onchange="previewPDF(this)">
      </div>
      <div id="pdfPreview" style="margin-top:10px; display:none;">
        <iframe class="card shadow" id="pdfFrame" style="width:100%; height:400px; border:1px solid #00192D;"></iframe>
      </div>
    </div>
  </div>
  <!-- Source of Income -->
   <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
    <div class="card-header" style="background-color: #00192D; color:#fff;">Source of Income</div>
    <div class="card-body text-center">
      <label>Main Source of Income</label>
      <div class="row">
        <div class="col-md-4">
          <input type="radio" id="incomeFormal" name="income" value="formal" required> <label for="incomeFormal">Formal Employment</label>
        </div>
        <div class="col-md-4">
          <input type="radio" id="incomeCasual" name="income" value="casual" required> <label for="incomeCasual">Casual Employment</label>
        </div>
        <div class="col-md-4">
          <input type="radio" id="incomeBusiness" name="income" value="business" required> <label for="incomeBusiness">Business</label>
        </div>
      </div>
      <!-- Formal -->
       <div id="formalPopup" class="popup" style="display:none;">
        <p>Specify Job Title &amp; Location:</p>
        <input type="text" id="formalWork" class="form-control" name="job_title" placeholder="Job Title">
        <input type="text" id="formalWorkLocation" class="form-control" name="job_location" placeholder="Job Location">
        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
      </div>
      <!-- Casual -->
       <div id="casualPopup" class="popup" style="display:none;">
        <p>Please Specify:</p>
        <input type="text" id="casualWork" class="form-control" name="casual_job">
        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
      </div>
      <!-- Business -->
       <div id="businessPopup" class="popup" style="display:none;">
        <p>Business Name and Location:</p>
        <input type="text" id="businessName" class="form-control" name="business_name" placeholder="Business Name">
        <input type="text" id="businessLocation" class="form-control" name="business_location" placeholder="Location">
        <button type="button" class="btn btn-sm mt-2 btn-outline-dark" onclick="closePopup()">OK</button>
      </div>
      <input type="hidden" name="tenant_status" value="Active">
    </div>
    <div class="card-footer text-right">
      <button type="submit" name="rent_unit" class="btn btn-sm" style="background-color: #00192D; color: #fff;">
        <i class="bi bi-check2-all"></i> Submit
      </button>
    </div>
  </div>
</form>
