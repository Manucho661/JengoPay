<div class="card" id="plumbingRequestDetails" style="display: none;">
    <div class="card-header"><b>Plumbing Request Details</b></div>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="plumbing_repairs" value="Plumbing Works">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Requested By</label>
                        <input type="text" class="form-control" id="requestedBy" data-toggle="tooltip"
                            title="Automatically Filled Content" value="Paul Pashan" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" id="requestedByPhone" data-toggle="tooltip"
                            title="Automatically Filled Content" value="0716691440" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" id="requestedByPhone" data-toggle="tooltip"
                            title="Automatically Filled Content" value="paulwamoka@gmail.com" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>House No.</label>
                        <input type="text" class="form-control" id="requestedByHseNo" data-toggle="tooltip"
                            title="Automaticall Filled Content" value="316" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Request To</label>
                        <input type="text" class="form-control" id="requestedByBuilding" data-toggle="tooltip"
                            title="Automaticall Filled Content" value="Biccount Technologies" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Building</label>
                        <input type="text" class="form-control" id="requestedByBuilding" data-toggle="tooltip"
                            title="Automaticall Filled Content" value="Crown Z Towers" readonly>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label>Plumbing Issue</label>
                <select class="form-control" name="plumbing_issue" id="plumbing_issue">
                    <option value="" hidden selected>-- Select Option --</option>
                    <option>Leaking Pipe</option>
                    <option>Faucet Issue</option>
                    <option>Showerhead Issue</option>
                    <option>Toilet Unclogging</option>
                    <option>Unclogging (Toilet or Sink)</option>
                    <option>Sewage and Septic Tank Issues</option>
                    <option>Installations</option>
                </select>
                <b class="text-danger mt-2" id="plumbingIssueError"></b>
            </div>
            <div class="form-group">
                <label>Detailed Description</label> <sup class="text-danger"><b>*</b></sup>
                <textarea name="plumbing_desc" id="plumbing_desc" class="form-control" required
                    placeholder="Write a brief Description of your Request Here"></textarea>
                <b class="text-danger mt-2" id="plumbingDescError"></b>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Urgence Level</label>
                        <select name="plumbing_urgence" id="plumbing_urgence" class="form-control">
                            <option value="" selected hidden>-- Select Option --
                            </option>
                            <option value="Immediate Attention Needed">Immediate
                                Attention Needed</option>
                            <option value="Urgent within 24 Hrs">Urgent within 24 Hrs
                            </option>
                            <option value="Next Available Appointment">Next Available
                                Appointment</option>
                        </select>
                        <b class="text-danger mt-2" id="plumbingUrgencyError"></b>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Today's Date</label>
                        <input type="text" class="form-control" name="" id="" value="<?php echo date('d, M Y') ;?>"
                            readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <label>Do you want to Attach Photos?</label>
                </div>
                <div class="col-md-6 text-center">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="customSwitchAttachPhotos"
                                name="plumbing_photos" value="Yes" onclick="displayToInsertPlumbingPhotos();" required>
                            <label class="custom-control-label" for="customSwitchAttachPhotos">
                                Yes</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="custom-control custom-switch">
                        <input type="radio" class="custom-control-input" id="customSwitchNo" name="plumbing_photos"
                            value="No" onclick="displayToHidePlumbingPhotos();" required>
                        <label class="custom-control-label" for="customSwitchNo">
                            No</label>
                    </div>
                </div>
            </div>
            <div class="card" id="plumbingPhotosDiv" style="display: none;">
                <div class="card-header"><b>Browse to Attach Photos</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Photo 1</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile"
                                            name="plumbing_photo_one">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Photo 2</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile"
                                            name="plumbing_photo_one">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Photo 3</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile"
                                            name="plumbing_photo_one">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Photo 4</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile"
                                            name="plumbing_photo_one">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" class="form-control" name="status" value="Pending">
            <hr>
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="radio" class="custom-control-input" id="customSwitchAuthorize" name="plumbing_photos"
                        value="Confirm Authorization">
                    <label class="custom-control-label" for="customSwitchAuthorize"> I
                        authorize the plumbing service provider to inspect and perform
                        the requested services. Depending on the extent of the required
                        service, I understand that additional charges may apply based on
                        the final assessment.</label>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="button" class="btn btn-sm text-light" style="background-color: #cc0001;"
                data-dismiss="modal"><i class="fa fa-close"></i> <b>Dismiss</b></button>
            <button type="submit" class="btn btn-sm" style="background-color: #00192D; color:#fff;"
                id="confirmPlumbingRequest"><i class="fa fa-check"></i>
                <b>Submit</b></button>
        </div>
    </form>
</div>