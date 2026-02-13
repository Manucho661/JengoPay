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
                        <form action="" method="post" autocomplete="off">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <form action="" method="post" autocomplete="off">
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-header"><b>Expenditure Details</b></div>
                                                <div class="card-body">
                                                    <div class="row" style="border: 1px solid #f2f2f2;">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Exp No.</label>
                                                                <input type='text' class="form-control" name="exp_no">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Exp Date</label>
                                                                <input type='date' class="form-control" name="exp_date">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label>Receiver</label>
                                                                <input type='text' class="form-control" name="cname">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Address</label>
                                                                <input type='text' class="form-control" name="caddress">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>City</label>
                                                                <input type='text' class="form-control" name="ccity">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2" style="border: 1px solid #f2f2f2;">
                                                        <div class="col-md-12">
                                                            <p class="text-center" style="font-weight: bold;">List Items and their item_totalss</p>
                                                            <table class="table table-striped mt-2">
                                                                <thead>
                                                                    <th>Paid For</th>
                                                                    <th>Description</th>
                                                                    <th>Unit Price</th>
                                                                    <th>Qty</th>
                                                                    <th>Taxation</th>
                                                                    <th>Total</th>
                                                                    <th>Remove</th>
                                                                </thead>
                                                                <tbody id='rantal_pay_table_body'>
                                                                    <tr>
                                                                        <td>
                                                                            <select name='paid_for[]' id='paid_for' class='form-control'>
                                                                                <option value='' selected hidden>Select Option</option>
                                                                                <option value='Rent'>Rent</option>
                                                                                <option value='Water Bill'>Water Bill</option>
                                                                                <option value='Garbage Collection'>Garbage Collection</option>
                                                                                <option value='Electricity'>Electricity</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <textarea name='paid_for_desc[]' id='paid_for_desc' cols='50' rows='1' class='form-control'></textarea>
                                                                        </td>
                                                                        <td>
                                                                            <input type='text' required name='unit_price[]' class='form-control unit_price' >
                                                                        </td>
                                                                        <td>
                                                                            <input type='text' required name='quantity[]' class='form-control quantity'>
                                                                        </td>
                                                                        <td>
                                                                            <select name='taxation[]' id='taxation' class='form-control taxation'>
                                                                                <option value='' selected hidden>Taxation</option>
                                                                                <option value='0'>0%</option>
                                                                                <option value='16'>16% VAT Inclusive</option>
                                                                                <option value='16'>16% VAT Exclusive</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type='text' required name='item_totals[]' class='form-control item_totals' readonly>
                                                                        </td>
                                                                        <td>
                                                                            <button class='btn btn-sm btn-row-remove' style='background-color:#cc0001;color:#fff;'>
                                                                                <i class='fa fa-trash'></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                       <td>
                                                                           <button type="button" class="btn btn-sm" id='btn-add-row' style="background-color:#000020;color: #fff;"><i class="fa fa-plus-square"></i> Add More</button>
                                                                       </td>
                                                                       <td></td>
                                                                       <td></td>
                                                                       <td colspan="2" class="text-right"><label>Sub Totals</label></td>
                                                                       <td><input type='text' class='form-control' name='sub_total' id="sub_total" readonly></td>
                                                                       <td></td>
                                                                       <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <label>Total Taxes</label>
                                                                                <input type="text" class="form-control" id="total_taxes" readonly name="total_taxes">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <label>Grand Total</label>
                                                                                <input type="text" class="form-control" id="total_after_tax" readonly name="total_after_tax">
                                                                            </div>
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-sm" data-dismiss="modal" style="background-color:#cc0001; color:#fff;">Close</button>
                                            <button type="submit" class="btn btn-sm" style="background-color:#000020; color:#fff;" name="submit_exp">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer"></div>
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

        <script type="text/javascript">
            $(document).ready(function(){

                //Add A new Row when the Add New Row Button is clicked.
                $("#btn-add-row").click(function(){
                    var tableRow = "<tr><td><select name='paid_for[]' id='paid_for' class='form-control'><option value='' selected hidden>Select Option</option><option value='Rent'>Rent</option><option value='Water Bill'>Water Bill</option><option value='Garbage Collection'>Garbage Collection</option><option value='Electricity'>Electricity</option></select></td><td><textarea name='paid_for_desc[]' id='paid_for_desc' cols='50' rows='1' class='form-control'></textarea></td><td><input type='text' required name='unit_price[]' class='form-control unit_price' ></td><td><input type='text' required name='quantity[]' class='form-control quantity'></td><td><select name='taxation[]' id='taxation' class='form-control taxation'><option value='' selected hidden>Taxation</option><option value='0'>0%</option><option value='16'>16% VAT Inclusive</option><option value='16'>16% VAT Exclusive</option></select></td><td><input type='text' required name='item_totals[]' class='form-control item_totals' readonly></td><td><button class='btn btn-sm btn-row-remove' style='background-color:#cc0001;color:#fff;'><i class='fa fa-trash'></i></button></td></tr>";
                    $("#rantal_pay_table_body").append(tableRow);
                });

                //Remove the row if the remove row button is clicked
                $("body").on("click", ".btn-row-remove",function(){
                    if(confirm("This item will be removed from the expense list. If you would wish to continue click OK")){
                        $(this).closest("tr").remove();
                        sub_total();
                    }
                });

                //Auto-Calculations
                $("body").on("keyup", ".unit_price",function(){
                    var unit_price = Number($(this).val());
                    var quantity = Number($(this).closest("tr").find(".quantity").val());
                    $(this).closest("tr").find(".item_totals").val(unit_price * quantity);
                    sub_total();
                });

                $("body").on("keyup", ".quantity", function(){
                    var quantity = Number($(this).val());
                    var unit_price = Number($(this).closest("tr").find(".unit_price").val());
                    $(this).closest("tr").find(".item_totals").val(unit_price * quantity);
                    sub_total();
                });

                function sub_total(){
                    var subTotal = 0;
                    $(".item_totals").each(function(){
                        subTotal+=Number($(this).val());
                    });
                    $("#sub_total").val(subTotal);
                }

            });
        </script>

    </body>

</html>
