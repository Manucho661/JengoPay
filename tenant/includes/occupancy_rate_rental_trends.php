<div class="row">
    <div class="col-lg-6">
        <!-- DONUT CHART Showing Occupancy Rate-->
        <div class="card card-danger">
            <div class="card-header" style="background-color:#cc0001;">
                <h3 class="card-title">Occupancy Rate</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"> <i class="fas fa-times"></i> </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="occupancyRate" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
        <!-- DONUT CHART Showing Occupancy Rate-->
    </div>
    <div class="col-lg-6">
        <!-- LINE CHART SHOWING RENTAL TRENDS-->
        <div class="card">
            <div class="card-header text-light" style="background-color:#cc0001;">
                <h3 class="card-title">Rental Trends</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"> <i class="fas fa-minus"></i> </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"> <i class="fas fa-times"></i> </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="myChartRentalTrends" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <!-- LINE CHART SHOWING RENTAL TRENDS-->
    </div>
</div>