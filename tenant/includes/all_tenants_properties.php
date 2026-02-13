<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header" style="background-color:rgba(0,25,45,0.9);">
                <h3 class="card-title text-light">All Tenants</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"> <i class="fas fa-times"></i> </button>
                </div>
            </div>
            <div class="card-body">
                <?php include_once 'includes/all_tenants.php';?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header" style="background-color:rgba(0,25,45,0.9);">
                <h3 class="card-title text-light">Properties</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"> <i class="fas fa-times"></i> </button>
                </div>
            </div>
            <div class="card-body">
                <?php include_once 'includes/dashboard_property_expandable_table.php';?>
            </div>
        </div>
    </div>
</div>