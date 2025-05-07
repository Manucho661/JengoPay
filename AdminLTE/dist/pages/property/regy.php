<div class="card-body">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Building Name</label> <sup class="text-danger"><b>*</b></sup>
                                  <input type="text" class="form-control" id="buildingName" name="building_name"
                                    placeholder="Building Name">
                                </div>
                              </div>
                            </div>
                            <h5 class="text-center" style="font-weight: bold;">Location
                              Information</h5>

                            <div class="row">
                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>County</label>
                                  <select name="county" id="county" onchange="loadConstituency()"  class="form-control select2 select2-danger"
                                    data-dropdown-css-class="select2-danger"
                                    style="width: 100%; height:300px !important;">
                                    <option value="" hidden selected>-- Select Option --</option>
                                    <option>Mombasa</option>
                                    <option>Kwale</option>
                                    <option>Kilifi</option>
                                    <option>Tana River</option>
                                    <option>Lamu</option>
                                    <option>Taita Taveta</option>
                                    <option>Garissa</option>
                                    <option>Wajir</option>
                                    <option>Mandera</option>
                                    <option>Marsabit</option>
                                    <option>Isiolo</option>
                                    <option>Meru</option>
                                    <option>Tharaka-Nithi</option>
                                    <option>Embu</option>
                                    <option>Kitui</option>
                                    <option>Machakos</option>
                                    <option>Makueni</option>
                                    <option>Nyandarua</option>
                                    <option>Nyeri</option>
                                    <option>Kirinyaga</option>
                                    <option>Murang'a</option>
                                    <option>Kiambu</option>
                                    <option>Turkana</option>
                                    <option>West Pokot</option>
                                    <option>Samburu</option>
                                    <option>Trans Nzoia</option>
                                    <option>Uasin Gishu</option>
                                    <option>Elgeyo/Marakwet</option>
                                    <option>Nandi</option>
                                    <option>Baringo</option>
                                    <option>Laikipia</option>
                                    <option>Nakuru</option>
                                    <option>Narok</option>
                                    <option>Kajiado</option>
                                    <option>Kericho</option>
                                    <option>Bomet</option>
                                    <option>Kakamega</option>
                                    <option>Vihiga</option>
                                    <option>Bungoma</option>
                                    <option>Busia</option>
                                    <option>Siaya</option>
                                    <option>Kisumu</option>
                                    <option>Homa Bay</option>
                                    <option>Migori</option>
                                    <option>Kisii</option>
                                    <option>Nyamira</option>
                                    <option>Nairobi</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>Constituency</label>
                                  <select name="constituency" id="constituency" onchange="loadWard()" class="form-control" required>
                                    <option value="" selected hidden>-- Choose
                                      Constituency
                                      --</option>


                                  </select>
                                  <b class="errorMessages" id="constituencyError"></b>
                                </div>
                              </div>


                              <div class="col-12 col-md-4">
                                <div class="form-group">
                                  <label>Ward</label>
                                  <select name="ward" id="ward"    class="form-control">
                                    <option value="" selected hidden>-- Choose Ward
                                      --
                                    </option>

                                  </select>
                                  <b class="errorMessages" id="wardError"></b>
                                </div>
                              </div>
                            </div>
        <div class="row">
       <div class="col-12 col-md-4">
            <div class="form-group">
            <label >Number of Floors</label>
            <input
              type="text"
              name="floor_number"
              class="form-control"
              id="floorNumber"
              placeholder="Number of Floors"
              required
              pattern="^\d+$"
              title="Please enter a valid number for the number of floors."
              oninput="validateFloorNumber()"
            />
            <small id="floorNumberError" style="color:red; display:none;">Please enter a valid number for the number of floors.</small>

            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
            <label>Number of Units</label>
            <input
              type="text"
              class="form-control"
              id="unitsnumber"
              name="units_number"
              placeholder="Number of Units"
              required
              pattern="^\d+$"
              title="Please enter a valid number for the number of units."
              oninput="validateUnitsNumber()"
            />
            <small id="unitsNumberError" style="color:red; display:none;">Please enter a valid number for the number of units.</small>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <label>Building Type</label>
            <select name="building_type" id="buildingType" class="form-control">
              <option value="" selected hidden>--Select Building
                Type--</option>
              <option value="Residential">Residential</option>
              <option value="Commercial">Commercial</option>
              <option value="Commercial">Commercial</option>
              <option value="Industrial">Industrial</option>
              <option value="Industrial">Industrial</option>
              <option value="Mixed-Use">Mixed-Use</option>
            </select>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepTwoBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepTwoNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Three Ownership Information -->
    <div class="card" id="sectionThree">
      <div class="card-header">
        <b>Ownership Information</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Building Owned By</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="ownership_info" id="showIndividualOwnerRadio"
                      onclick="showIndividualOwner();" value="Individual">
                    <label for="showIndividualOwnerRadio"> Individual
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="ownership_info" id="showEntityOwnerRadio"
                      onclick="showEntityOwner();" value="Entity" value="Individual">
                    <label for="showEntityOwnerRadio"> Entity
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div id="individualInfoDiv" style="display: none;">
              <div class="card">
                <div class="card-header"><b>Enter Individual's
                    Information</b></div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                      <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" id="firstName"
                          placeholder="First Name"  pattern="[A-Za-z]+"
                        title="Only letters allowed"
                        required
                        oninput="validateFirstName()" required>
                      <small id="firstNameError" style="color:red; display:none;">Only letters allowed</small>

                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                      <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" id="lastName"
                          placeholder="Last Name"  pattern="[A-Za-z]+"
                        title="Only letters allowed"
                        required
                        oninput="validateLastName()">
                      <small id="lastNameError" style="color:red; display:none;">Only letters allowed</small>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                  <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="phoneNumber"
                     pattern="07[0-9]{8}" maxlength="10"
                      placeholder="Phone Number"  title="Phone number must start with 07 and be 10 digits long"
                    required
                    oninput="validatePhoneNumber()">
                  <small id="phoneNumberError" style="color:red; display:none;">Phone must start with 07 and be 10 digits</small>
                  </div>

                  <div class="form-group">
                <label >KRA PIN</label>
                <input
                  type="text"
                  name="kra_pin"
                  class="form-control"
                  id="kra_pin"
                  placeholder="KRA PIN (e.g. A123456789K)"
                  pattern="^[A-Z]{1}\d{9}[A-Z]{1}$"
                  title="KRA PIN must be in the format A123456789K"
                  required
                  oninput="validateKraPin()"
                />
                <small id="kraPinError" style="color:red; display:none;">Format: A123456789K (1 letter, 9 digits, 1 letter)</small>

                  </div>
                  <div class="form-group">
                  <label>Email</label>
                  <input
                    type="email"
                    name="email"
                    class="form-control"
                    id="ownerEmail"
                    placeholder="Enter a valid email (e.g. name@example.com)"
                    required
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                    title="Enter a valid email address"
                    oninput="validateEmail()"
                  >
                  <small id="emailError" style="color:red; display:none;">Please enter a valid email address.</small>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button type="button" class="btn btn-sm"
                    style="background-color: #cc0001; color:#fff;"
                    id="individualCloseBtn">Close</button>
                </div>
              </div>
            </div>
            <div id="entityInfoDiv" style="display: none;">
              <div class="card">
                <div class="card-header"><b>Enter Entity's
                    Information</b></div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                      <label >Entity Name</label>
                      <input
                        type="text"
                        name="entity_name"
                        class="form-control"
                        id="entityName"
                        placeholder="Entity Name"
                        required
                        pattern="^[A-Za-z\s]+$"
                        title="Only letters and spaces are allowed"
                        oninput="validateEntityName()"
                      >
                      <small id="entityNameError" style="color:red; display:none;">
                        Only letters and spaces are allowed.
                      </small>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                      <label>Official Phone Number</label>
                      <input type="text" name="entity_phone" class="form-control" id="entityPhone"  pattern="07[0-9]{8}"  maxlength="10"
                      placeholder="Official Phone Number" oninput="validateEntityPhone()" required>
                      <small id="entityPhoneError" style="color: red; display: none;">
                        Phone must start with 07 and be 10 digits
                      </small>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                  <label >Official Email</label>
                  <input
                    type="email"
                    name="entity_email"
                    class="form-control"
                    id="entityEmail"
                    placeholder="Enter a valid email (e.g. name@example.com)"
                    required
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                    title="Enter a valid email address"
                    oninput="validateEntityEmail()"
                  />
                  <small id="entityEmailError" style="color:red; display:none;">Please enter a valid email address.</small>

                  </div>
                  <div class="form-group">
                  <label >Kra pin</label>
                  <input
                  type="text"
                  name="entity_kra_pin"
                  class="form-control"
                  id="kra_pin"
                  placeholder="KRA PIN (e.g. A123456789K)"
                  pattern="^[A-Z]{1}\d{9}[A-Z]{1}$"
                  title="KRA PIN must be in the format A123456789K"
                  required
                  oninput="validateKraPin()"
                />
                <small id="kraPinError" style="color:red; display:none;">Format: A123456789K (1 letter, 9 digits, 1 letter)</small>


                  </div>
                  <div class="form-group">
                  <label >Entity Representative</label>
                  <input
                    type="text"
                    name="entity_representative"
                    class="form-control"
                    id="entityRepresentative"
                    placeholder="Entity Representative (letters only)"
                    pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed"
                    required
                    oninput="validateEntityRepresentative()"
                  />
                  <small id="entityRepresentativeError" style="color:red; display:none;">
                    Only letters and spaces are allowed.
                  </small>

                  </div>

                  <div class="form-group">
                    <label>Role</label>
                    <select name="entity_rep_role" id="entityRepRole" class="form-control">
                      <option value="" selected hidden>
                        --Select Role --</option>
                      <option value="CEO">CEO</option>
                      <option value="Treasury">Treasury
                      </option>
                      <option value="Board Member">Board
                        Member</option>
                      <option value="Signatory">Signatory
                      </option>
                      <option value="Founder">Founder
                      </option>
                      <option value="Co-Founder">
                        Co-Founder</option>
                    </select>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" id="entityCloseDivBtn"
                    style="background-color: #cc0001; color:#fff">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <label>Title Deed Copy</label>
                  <input type="file" onchange="handleFiles(event)" class="form-control" id="titleDeedCopy">
                  <!-- <small id="fileSizeError" style="color:red; display:none;">The file size must not exceed 2MB.</small> -->
                  <!-- Section to display selected files' previews and sizes -->
                  <div id="filePreviews"></div>

                </div>
                <div class="col-md-6">
                  <label>Other Legal Document</label>
                  <input type="file" onchange="handleFiles(event)" class="form-control" id="otherDocumentCopy">

                  <!-- Section to display selected files' previews and sizes -->
                  <div id="filePreviews"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepThreeBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepThreeNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Four Utilities and Infrastructure -->
    <div class="card" id="sectionFour">
      <div class="card-header"><b>Utilities and Infrastructure</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Is there a Borehole?</label>
              <select name="borehole_availability" id="boreHoleVailability" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Do you Have Solar System?</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="solar_availability" id="solarAvailabilityYes"
                      onclick="specifySolarProvider();" value="Yes">
                    <label for="solarAvailabilityYes"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="solar_availability" id="solarAvailabilityNo"
                      onclick="hideSolarProvider();" value="No">
                    <label for="solarAvailabilityNo"> No
                    </label>
                  </div>
                </div>
              </div>
              <div class="card mt-2" id="specifySolarPrivider" style="display: none;">
                <div class="card-header"><b>Please Specify</b></div>
                <div class="card-body">
                  <div class="form-group">
                  <label>Solar Panel Brand</label>
                  <input
                    type="text"
                    name="solar_brand"
                    class="form-control"
                    id="solarBrand"
                    placeholder="Solar Brand (letters only)"
                    pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed"
                    required
                    oninput="validateSolarBrand()"
                  />
                  <small id="solarBrandError" style="color:red; display:none;">
                    Only letters and spaces are allowed.
                  </small>

                  </div>
                  <div class="form-group">
                  <label >Installation Company</label>
                  <input
                    type="text"
                    name="installation_company"
                    class="form-control"
                    id="installationCompany"
                    placeholder="Installation Company (letters only)"
                    pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed"
                    required
                    oninput="validateInstallationCompany()"
                  />
                  <small id="installationCompanyError" style="color:red; display:none;">
                    Only letters and spaces are allowed.
                  </small>

                  </div>
                  <div class="form-group">
                  <label >Number of Panels</label>
                  <input
                    type="text"
                    name="no_of_panels"
                    class="form-control"
                    id="noOfPanels"
                    placeholder="Enter number of panels"
                    pattern="^\d+$"
                    title="Only numbers are allowed"
                    required
                    oninput="validateNoOfPanels()"
                  />
                  <small id="noOfPanelsError" style="color:red; display:none;">
                    Only numeric values are allowed.
                  </small>

                  </div>
                  <div class="form-group">
                    <label>Primary Use</label>
                    <select name="solar_primary_use" id="solarPrimaryUse" class="form-control">
                      <option value="" selected hidden>-- Select Option --</option>
                      <option value="Lighting">Lighting</option>
                      <option value="Water Heating">Water Heating</option>
                      <option value="Power Backup">Power Backup</option>
                      <option value="Multi-Purpose">Multi-Purpose</option>
                    </select>
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" type="button"
                    style="background-color: #cc0001; color:#fff;"
                    id="closeSolarProviderBtn">Close</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Is there Parking Lot?</label>
              <select name="parking_lot" id="parkingLot" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Is there Alarm Security System?</label>
              <select name="alarm_system" id="alarmSystem" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Is there Elevator(s)?</label>
              <select name="elevators" id="elevators" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Is there PSD's Accessibility?</label>
              <select name="psds_accessibility" id="psds" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="form-group">
              <label>Is there CCTV?</label>
              <select name="cctv" id="cctv" class="form-control">
                <option value="" selected hidden>-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepFourBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepFourNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Five Legal and Regulatory Details -->
    <div class="card" id="sectionFive">
      <div class="card-header"><b>Legal and Regulatory Details</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Do you have NCA Approval</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nca_approval" onclick="attachNcaApproval();"
                      value="Yes" id="showNcaContents">
                    <label for="showNcaContents"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nca_approval" onclick="closeAttachNcaApproval();"
                      value="No" id="noNcaContent">
                    <label for="noNcaContent"> No
                    </label>
                  </div>

                </div>
              </div>
            </div>
            <div class="card" id="ncaApprivalCard" style="display:none;">
              <div class="card-header"><b>Construction Approval</b></div>
              <div class="card-body">
                <div class="form-group">
                <label>Approval Number</label>
                <input
                  type="text"
                  name="nca_approval_no"
                  class="form-control"
                  id="approvalNo"
                  placeholder="Approval Number (e.g. NCA-1234567-2025)"
                  pattern="^NCA-\d{7}-\d{4}$"
                  title="Format must be NCA-1234567-2025"
                  required
                  oninput="validateApprovalNo()"
                />
                <small id="approvalNoError" style="color:red; display:none;">
                  Format must be NCA-1234567-2025
                </small>

                </div>
                <div class="form-group">
                <label >Approval Date</label>
                <input
                  type="date"
                  name="date"
                  class="form-control"
                  id="approvalDate"
                  max="<?php echo date('Y-m-d'); ?>"
                  required
                />
                <small id="approvalDateError" style="color:red; display:none;">
                  Approval date cannot be in the future.
                </small>

                </div>
                <div class="formm-control">
                  <label>NCA Approval Copy</label>
                  <input type="file"  class="form-control" id="ncaApprovalCopy">
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-sm" id="closeNcaApprovalBtn" type="button"
                  style="background-color: #cc0001; color:#fff;">Close</button>
              </div>
            </div>
            <div class="form-group">
              <label>Do You a Local Government Approval</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="local_gov_approval"
                      onclick="showLocalGovernmentApproval();" value="Yes" id="localGovApproval">
                    <label for="localGovApproval"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="local_gov_approval"
                      onclick="hideLocalGovernmentApproval();" value="Yes" id="noLocalGov">
                    <label for="noLocalGov"> No
                    </label>
                  </div>
                </div>
              </div>
              <div class="card" id="localGovSpecifications" style="display: none;">
                <div class="card-header"><b>Local Government Approval Details</b></div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Approval Number</label>
                    <input type="text" name="local_gov_approval_no" class="form-control" id="localGovApprovalNo">
                  </div>
                  <div class="form-group">
                    <label>Approval Date</label>
                    <input type="date" name="local_gov_approval_date" class="form-control" id="localGovApprovalDate">
                  </div>
                  <div class="form-group">
                    <label>Approval Copy</label>
                    <input type="file" class="form-control" id="localGovApprovalCopy">
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" id="closeLocalGovSpecifications" type="button"
                    style="background-color: #cc0001; color:#fff;">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Do you have NEMA Approval</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nema_approval" onclick="nemaApprovalShow();"
                      id="nemaApprovalYes" value="Yes">
                    <label for="nemaApprovalYes"> Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="nema_approval" onclick="nemaApprovalHide();"
                      id="nemaApprovalNo" value="No">
                    <label for="nemaApprovalNo"> No
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="card" id="nemaApprovalSpecify" style="display: none;">
              <div class="card-header"><b>NEMA Approval Specifications</b></div>
              <div class="card-body">
                <div class="form-group">
                <label>Approval Number</label>
                <input
                  type="text"
                  name="nema_approval_no"
                  class="form-control"
                  id="nemaApprovalNumber"
                  placeholder="Approval Number (e.g. NEMA/EIA/PS/1234)"
                  pattern="^NEMA\/EIA\/PS\/\d{4}$"
                  title="Format: NEMA/EIA/PS/1234"
                  required
                  oninput="validateNemaApproval()"
                />
                <small id="nemaApprovalError" style="color:red; display:none;">
                  Format must be NEMA/EIA/PS/1234
                </small>

                </div>
                <div class="form-group">
                <label>Approval Date</label>
                <input
                  type="date"
                  name="nema_approval_date"
                  class="form-control"
                  id="nemaApprovalDate"
                  required
                />
                <small id="nemaDateError" style="color:red; display:none;">Date cannot be in the future.</small>


                </div>
                <div class="form-group">
                  <label>Approval Copy</label>
                  <input type="file"  class="form-control" id="nemaApprovalCopy">
                </div>
              </div>
              <div class="card-footer text-right">
                <button class="btn btn-sm" id="closeNemaApproval" type="button"
                  style="background-color: #cc0001; color:#fff;">Close</button>
              </div>
            </div>
            <div class="form-group">
            <label >TAX PIN for the Building</label>
                <input
                  type="text"
                  name="building_tax_pin"
                  class="form-control"
                  id="buildingTaxPin"
                  placeholder="TAX PIN for the Building (e.g. P123456789B)"
                  required
                  pattern="^P\d{9}B$"
                  title="TAX PIN must be in the format P123456789B"
                  oninput="validateTaxPin()"
                />
                <small id="taxPinError" style="color:red; display:none;">Format: P123456789B (1 letter, 9 digits, 1 letter)</small>

            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepFiveBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepFiveNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Six Insurance Information -->
    <div class="card" id="sectionSix">
      <div class="card-header"><b>Insurance and Financial Information</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Do your Building have Insurance Cover?</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="icheck-dark d-inline">
                    <input type="radio" name="insurance_cover" id="yesInsurance"
                      onclick="insuranceCoverYes();" value="Yes">
                    <label for="yesInsurance"> Yes
                  </div>

                </div>
                <div class="col-md-6">
                <div class="icheck-dark d-inline">
                    <input type="radio" name="insurance_cover" id="noInsurance"
                      onclick="insuranceCoverYes();" value="No">
                    <label for="noInsurance"> No
                  </div>


                </div>
              </div>
              <div class="card mt-2" id="specifyInsuranceCoverInfoCard" style="display: none;">
                <div class="card-header"><b>Insurance Cover Details</b></div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Specify Insurance Policy</label>
                    <input type="text" name="insurance_policy" class="form-control" id="insurance_policy"
                      placeholder="Insurance Policy">
                  </div>
                  <div class="form-group">
                    <label>Insurance Policy Provider</label>
                    <input type="text" class="form-control" name="insurance_provider" id="insurance_provider"
                      placeholder="Insurance Policy Provider">
                  </div>
                  <div class="form-group">
                    <label>Covered From</label>
                    <input type="date" class="form-control"  name="policy_from_date"  id="policy_from_date">
                  </div>
                  <div class="form-group">
                    <label>Covered Until</label>
                    <input type="date" name="policy_until_date" class="form-control" id="policy_until_date">
                  </div>
                </div>
                <div class="card-footer text-right">
                  <button class="btn btn-sm" id="closeInsuranceInfoBtn"
                    style="background-color: #cc0001; color:#fff">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6"></div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepSixBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepSixNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Seven Photos -->
    <div class="card" id="sectionSeven">
      <div class="card-header"><b>Photographs and Documentations</b></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Front View</label>
              <input type="file" class="form-control"  name="front_view_photo" id="front_view_photo">
            </div>
            <div class="form-group">
              <label>Rear View</label>
              <input type="file" class="form-control" name="rear_view_photo" id="front_view_photo">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Angle View</label>
              <input type="file" class="form-control" name="angle_view_photo" id="angle_view_photo">
            </div>
            <div class="form-group">
              <label>Interior</label>
              <input type="file" class="form-control" name="interior_view_photo" id="interior_view_photo">
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepSevenBackBtn">Back</button>
        <button type="button" class="btn btn-sm next-btn" id="stepSevenNextBtn">Next</button>
      </div>
    </div>
    <!-- Section Eight Confirmation -->
    <div class="card" id="sectionEight">
      <div class="card-header"><b>Confirmation</b></div>
      <div class="card-body text-center">
        <input type="checkbox" required> I here by confirm that all the
        information filled in this form is accurare. I therefore issue my
        consent to Biccount Technologies to go ahead and register my rental
        property for further property management services that I will be
        receiving.
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-danger btn-sm back-btn"
          id="stepEightBackBtn">Back</button>
        <button type="submit" class="btn btn-sm next-btn" id="stepEightNextBtn">Submit</button>
      </div>
    </div>
  </form>