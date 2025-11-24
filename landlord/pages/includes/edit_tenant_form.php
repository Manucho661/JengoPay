<form action="" method="post" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="id" value="<?= htmlspecialchars($tenant['id']) ;?>">
  <!-- Tenant Personal Information -->
  <div class="card shadow" style="border:1px solid rgba(0,25,45,.3);">
    <div class="card-header" style="background-color: #00192D; color:#fff;">Personal Information</div>
    <div class="card-body">
      <!-- Name Details -->
       <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="tfirst_name" class="form-control shadow" placeholder="First Name" value="<?= htmlspecialchars($tenant['tfirst_name']);?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="tmiddle_name" name="tmiddle_name" class="form-control shadow" placeholder="Middle Name" value="<?= htmlspecialchars($tenant['tmiddle_name']);?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="tlast_name" class="form-control shadow" placeholder="Last Name" value="<?= htmlspecialchars($tenant['tlast_name']);?>">
          </div>
        </div>
      </div>
      <!-- Contact Details -->
       <div class="row">
        <div class="col-md-3">
          <div class="for-group">
            <label for="main_contact" class="form-label">Main WhatsApp Contact</label>
            <input id="main_contact" type="tel" name="tmain_contact" class="form-control shadow" placeholder="Enter phone number" required value="<?= htmlspecialchars('+254'.$tenant['tmain_contact']);?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="alt_contact" class="form-label">Alternative Contact</label>
            <input id="alt_contact" type="tel" name="talt_contact" class="form-control shadow" placeholder="Alternative phone number" required value="<?= htmlspecialchars('+254'.$tenant['talt_contact']);?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-group">
              <input type="email" id="email" name="temail" required class="form-control shadow" placeholder="Email" value="<?= htmlspecialchars($tenant['temail']);?>">
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <!-- ID Mode Radio Buttons -->
           <div class="form-group mb-3">
            <label><b>Identification Mode:</b></label> <sup data-togle="tooltip" title="Identification Mode and Number Can't be Changed"><i class="fa fa-question-circle"></i></sup>
            <div class="row">
              <?php
                if(htmlspecialchars($tenant['idMode']) == 'national') {
                  ?>
                    <div class="col-md-6">
                      <input class="form-control shadow" type="text" name="idMode" value="<?= htmlspecialchars(ucfirst($tenant['idMode'])) ;?>" readonly>
                    </div>
                    <div class="col-md-6">
                      <input type="number" id="nationalId" class="form-control shadow" placeholder="ID Number" name="id_no" pattern="[0-9]{6,10}" value="<?= htmlspecialchars($tenant['id_no']);?>" readonly>
                    </div>
                  <?php
                } else {
                  ?>
                    <div class="col-md-6">
                      <input class="form-control shadow" type="text" name="idMode" value="<?= htmlspecialchars(ucfirst($tenant['idMode'])) ;?>" readonly>
                    </div>
                    <div class="col-md-6">
                      <input type="number" id="nationalId" class="form-control shadow" placeholder="ID Number" name="id_no" pattern="[0-9]{6,10}" value="<?= htmlspecialchars($tenant['id_no']);?>" readonly>
                    </div>
                  <?php
                }
              ?>
            </div>
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
            <input type="number" id="leasingPeriod" required class="form-control shadow" name="leasing_period" value="<?=htmlentities($tenant['leasing_period']);?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="leasingStart">Leasing Starts On</label>
            <input type="date" id="leasingStart" required class="form-control shadow" name="leasing_start_date" value="<?=htmlentities($tenant['leasing_start_date']);?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="leasingEnd">Leasing Ends On</label>
            <input type="date" id="leasingEnd" readonly class="form-control shadow" name="leasing_end_date" value="<?=htmlentities($tenant['leasing_end_date']);?>">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <div class="form-group">
            <label for="moveIn">Move In Date</label>
            <input type="date" id="moveIn" required class="form-control shadow" name="move_in_date" value="<?=htmlentities($tenant['move_in_date']);?>">
          </div>
          <div class="form-group">
            <label for="moveOut">Move Out Date</label>
            <input type="date" id="moveOut" readonly class="form-control shadow" name="move_out_date" value="<?=htmlentities($tenant['move_out_date']);?>">
          </div>
          <div class="form-group">
            <label for="account_no">Unit Number</label>
            <input type="text" id="account_no" name="account_no" required class="form-control shadow" value="<?= htmlspecialchars($tenant['account_no']); ?>" readonly>
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
        <input type="hidden" name="id_upload_old" value="<?= htmlspecialchars($tenant['id_upload']);?>">
        <input type="file" id="id_upload" required name="id_upload" class="form-control shadow" accept=".jpg,.jpeg,.png,.pdf" onchange="previewIdUpload(this)">
        <img src="<?= htmlspecialchars($tenant['id_upload']);?>" width="20%" class="mt-2 shadow">
      </div>
      <div id="idPreview" style="margin-top:10px; display:none;"></div>
      <div class="form-group">
        <label for="tax_pin_copy">TAX PIN Upload</label><br>
        <input type="hidden" name="tax_pin_copy_old" value="<?= htmlspecialchars($tenant['tax_pin_copy']);?>">
        <input type="file" id="tax_pin_copy" name="tax_pin_copy" required class="form-control shadow" accept=".jpg,.jpeg,.png" onchange="previewTaxPinCopy(this)">
        <img src="<?= htmlspecialchars($tenant['tax_pin_copy']);?>" width="30%" class="mt-2 shadow">
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
          <?php
          if(htmlspecialchars($tenant['income']) == 'formal') {
            ?>
              <div class="form-group">
                  <label>Employment Category</label>
                  <input type="text" id="incomeFormal" name="income" value="<?= htmlspecialchars(ucfirst($tenant['income'])) ;?>" readonly class="form-control shadow">
              </div>
            <?php
          } else if (htmlspecialchars($tenant['income']) == 'casual') {
            ?>
              <div class="form-group">
                <label>Employment Category</label>
                <input type="text" id="incomeFormal" name="income" value="<?= htmlspecialchars(ucfirst($tenant['income'])) ;?>" readonly class="form-control shadow">
              </div>
            <?php
          } else {
            ?>
              <div class="form-group">
                <label>Employment Category</label>
                <input type="text" id="incomeFormal" name="income" value="<?= htmlspecialchars(ucfirst($tenant['income'])) ;?>" readonly class="form-control shadow">
              </div>
            <?php
          }
        ?>
        </div>
        <?php
          if(htmlspecialchars($tenant['income']) == 'formal') {
            ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Job Title</label>
                  <input type="text" class="form-control" name="job_title" placeholder="Job Title" value="<?= htmlspecialchars(ucfirst($tenant['job_title']));?>">
                </div>
              </div>
              <div class="col-md-4">
                <label>Employer</label>
                <input type="text" class="form-control" name="job_location" placeholder="Job Location" value="<?= htmlspecialchars(ucfirst($tenant['job_location']));?>">
              </div>
            <?php
          } else if (htmlspecialchars($tenant['income']) == 'Casual') {
            ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Job Title</label>
                  <input type="text" class="form-control" name="casual_job" placeholder="Job Title" value="<?= htmlspecialchars(ucfirst($tenant['casual_job']));?>">
                </div>
              </div>
            <?php
          } else {
            ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Business Name</label>
                  <input type="text" class="form-control" name="business_name" value="<?= htmlspecialchars(ucfirst($tenant['business_name']));?>">
                </div>
                <div class="form-group">
                  <label>Business Location</label>
                  <input type="text" class="form-control" name="business_location" value="<?= htmlspecialchars(ucfirst($tenant['business_location']));?>">
                </div>
              </div>
            <?php
          }
        ?>
      </div>
      <input type="hidden" name="tenant_status" value="Active">
    </div>
    <div class="card-footer text-right">
      <button type="submit" name="rent_unit" class="btn btn-sm" style="background-color: #00192D; color: #fff;">
        <i class="fa fa-check"></i> Update Info
      </button>
    </div>
  </div>
</form>
