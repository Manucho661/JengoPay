<div class="card shadow">
            <div class="card-header" style="background-color: #00192D; color: #fff;">
              <b>Create Invoice for <?= htmlspecialchars($tenant_info['tfirst_name'].' '.($tenant_info['tmiddle_name']).' '.($tenant_info['tlast_name']));?></b>
            </div>
            <form id="invoiceForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" enctype="multipart/form-data" autocomplete="off">
                <div class="card-body">
                    <!-- Tenant Info Section -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Invoice Number:</label>
                                <input type="text" id="invoiceNumber" name="invoice_no" required class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Invoice To:</label>
                                <input type="text" name="receiver" required class="form-control" value="<?= htmlspecialchars($tenant_info['tfirst_name'].' '.$tenant_info['tmiddle_name'].' '.$tenant_info['tlast_name']);?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Main Contact</label>
                                <input class="form-control" value="<?= htmlspecialchars($tenant_info['tmain_contact']);?>" readonly name="main_contact">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Alternative Contact</label>
                                <input class="form-control" value="<?= htmlspecialchars($tenant_info['talt_contact']);?>" readonly name="alt_contact">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Email</label>
                            <input class="form-control" value="<?= htmlspecialchars($tenant_info['temail']); ?>" readonly name="email">
                        </div>
                        <div class="col-md-4">
                            <label>Invoice Date</label>
                            <input type="date" id="invoiceDate" name="invoice_date" required class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Date Due</label>
                            <input type="date" id="dateDue" name="due_date" required class="form-control" readonly>
                        </div>
                    </div>

                    <hr>
                    <input type="hidden" name="paymentStatus" value="Pending">
                    <input type="hidden" name="monthly_rent" id="monthlyRent" value="<?= htmlspecialchars($monthly_rent); ?>">
                    <input type="hidden" name="final_bill" id="finalBill" value="<?= htmlspecialchars($final_bill); ?>">

                    <!-- Invoice Items Table -->
                    <h5 class="mb-3">Invoice Items</h5>
                    <table id="invoiceTable" class="table table-bordered table-striped shadow">
                        <thead class="table-dark">
                            <tr>
                                <th>Paid For</th>
                                <th>Description</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Taxation</th>
                                <th>Tax Amount</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceBody"></tbody>
                        <tfoot>
                            <tr><td colspan="6" class="text-end">Subtotal:</td><td id="subtotal" class="text-end">0.00</td><td></td></tr>
                            <tr><td colspan="6" class="text-end">Total Tax:</td><td id="totalTax" class="text-end">0.00</td><td></td></tr>
                            <tr><td colspan="6" class="text-end"><strong>Final Total:</strong></td><td id="finalTotal" class="text-end">0.00</td><td></td></tr>
                        </tfoot>
                    </table>

                    <hr>
                    <!-- Changed addRow() to open the drawer -->
                    <button type="button" onclick="openAddItemDrawer()" class="btn btn-sm shadow text-white" style="background-color:#00192D;">
                        <i class="fa fa-plus"></i> Add Item
                    </button>
                    <hr>

                    <!-- Notes & Attachment Section -->
                    <div class="row mb-3 shadow p-3 rounded">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Attachment</label>
                                <input type="file" name="attachment" accept=".pdf,.jpg,.png,.docx" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="invoice_items" id="invoiceItems">
                    <input type="hidden" name="subtotalValue" id="subtotalValue">
                    <input type="hidden" name="totalTaxValue" id="totalTaxValue">
                    <input type="hidden" name="finalTotalValue" id="finalTotalValue">
                </div>
                <div class="card-footer text-right">
                    <button type="submit" onclick="return prepareInvoiceData()" name="submit" class="btn btn-sm shadow" style="border:1px solid #00192D; color:#00192D;"><i class="fa fa-check"></i> Submit Invoice</button>
                </div>
            </form>
          </div>
        </div>
        <!-- Offcanvas (Side Panel) for Add Item -->
        <div class="offcanvas-right shadow" id="addItemDrawer">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Add New Invoice Item</h5>
            <button type="button" class="btn-close shadow" aria-label="Close" onclick="closeAddItemDrawer()" id="closeAddItem"><i class="fa fa-close"></i></button>
        </div>
            <form id="addItemForm">
                <div class="mb-3">
                    <label for="drawerItemName" class="form-label">Paid For <span class="text-danger">*</span></label>
                    <select class="form-control" id="drawerItemName" onchange="checkDrawerOthersInput(this)" required>
                        <!-- Options will be populated by JS -->
                    </select>
                    <input type="text" class="form-control mt-2 d-none" id="drawerOtherInput" placeholder="Please specify">
                </div>
                <div class="mb-3">
                    <label for="drawerDescription" class="form-label">Description</label>
                    <input type="text" class="form-control" id="drawerDescription">
                </div>
                <div class="mb-3">
                    <label for="drawerUnitPrice" class="form-label">Unit Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="drawerUnitPrice" step="0.01" value="0" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="drawerQuantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="drawerQuantity" value="1" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="drawerTaxType" class="form-label">Taxation</label>
                    <select class="form-control" id="drawerTaxType">
                        <option value="VAT Inclusive">VAT 16% Inclusive</option>
                        <option value="VAT Exclusive">VAT 16% Exclusive</option>
                        <option value="Zero Rated">Zero Rated</option>
                        <option value="Exempted">Exempted</option>
                    </select>
                </div> <hr>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-sm shadow text-white" style="background-color:#00192D;" style="background-color:#00192D;"><i class="fa fa-plus"></i> Add Item</button>
                    <button type="button" class="btn btn-sm text-white shadow" onclick="closeAddItemDrawer()" style="background-color:#cc0001;"><i class="fa fa-close"></i> Cancel</button>
                </div>
            </form>
        </div>
        <div class="offcanvas-backdrop" id="addItemDrawerBackdrop" onclick="closeAddItemDrawer()"></div>