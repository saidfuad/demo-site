<style>

    .k-grid td {
        white-space: nowrap;
        font-family: "DejaVu Sans", "Arial", sans-serif;
    }

    .k-grid-toolbar {
        background: #131b26 !important;
        border: none !important;
    }

    .k-grid-pdf {
        background: #c1d72e !important;
        border: none !important;
    }

    .k-grid-excel {
        background: #c1d72e !important;
        border: none !important;
        color: #131b26 !important;
        font-weight: bold !important;
    }

    /* Page Template for the exported PDF */
    .page-template {
        font-family: "DejaVu Sans", "Arial", sans-serif;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    .page-template .header {
        position: absolute;
        top: 30px;
        left: 30px;
        right: 30px;
        border-bottom: 1px solid #888;
        color: #888;
    }

    .page-template .header #title {
        text-align: center;
        font-size: 18px;
    }

    .page-template .footer {
        position: absolute;
        bottom: 30px;
        left: 30px;
        right: 30px;
        border-top: 1px solid #888;
        text-align: center;
        color: #888;
    }

    .page-template .watermark {
        font-weight: bold;
        font-size: 400%;
        text-align: center;
        margin-top: 30%;
        color: #aaaaaa;
        opacity: 0.1;
        transform: rotate(-35deg) scale(1.7, 1.5);
    }

    /* Content styling */
    .customer-photo {
        display: inline-block;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-size: 32px 35px;
        background-position: center center;
        vertical-align: middle;
        line-height: 32px;
        box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0, 0, 0, .2);
        margin-left: 5px;
    }

    kendo-pdf-document .customer-photo {
        border: 1px solid #dedede;
    }

    .customer-name {
        display: inline-block;
        vertical-align: middle;
        line-height: 32px;
        padding-left: 3px;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header"> </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive" style="margin-top:0px;">
                            <div class="tabbable">
                                <!-- Only required for left/right tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a id="badge1" href="#general" data-toggle="tab">General Reports</a>
                                    </li>
                                    <li>
                                        <a id="badge2" href="#mileage" data-toggle="tab">Mileage Reports</a>
                                    </li>
                                    <li>
                                        <a id="badge3" href="#alerts" data-toggle="tab">Alert Reports</a>
                                    </li>
                                    <li>
                                        <a id="badge4" href="#purchases" data-toggle="tab">Purchase Reports</a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    <!-- General -->
                                    <div class="tab-pane active" id="general">

                                        <div class="row" id="controls">

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Plate No</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-car fa fa-car'></i>
                                                                        </span>
                                                                        <select type="text" style="width: 200px" id="plate_noG" class="form-control">
                                                                            <option value="null">All Vehicles</option>
                                                                            <?php foreach ($vehicles as $key => $value) { ?>
                                                                            <option value="<?php echo $value->plate_no ; ?>"><?php echo $value->plate_no ; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Date</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-calendar fa fa-calendar'></i>
                                                                        </span>
                                                                        <input type="text" style="width: 200px" id="reportrangeG" class="form-control" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <!--<div class="col-xs-3">
                                                <h6><b>Filter by Sum</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls' style="margin-top: 8px;">
                                                                    <input id="sumG" type="checkbox">  &nbsp; Totals
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>-->

                                            <div class="col-xs-3">
                                                <div style="margin-top:38px;">
                                                    <button class="btn btn-xs btn-primary" id="generateG">Generate Report</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="general_table"></div>

                                    </div>

                                    <!-- Mileage -->

                                    <div class="tab-pane" id="mileage">

                                        <div class="row" id="controls">

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Plate No</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-car fa fa-car'></i>
                                                                        </span>
                                                                        <select type="text" style="width: 200px" id="plate_noM" class="form-control">
                                                                            <option value="null">All Vehicles</option>
                                                                            <?php foreach ($vehicles as $key => $value) { ?>
                                                                            <option value="<?php echo $value->plate_no ; ?>"><?php echo $value->plate_no ; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Date</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-calendar fa fa-calendar'></i>
                                                                        </span>
                                                                        <input type="text" style="width: 200px" id="reportrangeM" class="form-control" value="" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <!--<div class="col-xs-3">
                                                <h6><b>Filter by Sum</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls' style="margin-top: 8px;">
                                                                    <input id="sumM" type="checkbox">  &nbsp; Totals
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>-->

                                            <div class="col-xs-3">
                                                <div style="margin-top:38px;">
                                                    <button class="btn btn-xs btn-primary" id="generateM">Generate Report</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="mileage_table"></div>

                                    </div>

                                    <!-- Alerts -->

                                    <div class="tab-pane" id="alerts">

                                        <div class="row" id="controls">

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Plate No</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-car fa fa-car'></i>
                                                                        </span>
                                                                        <select type="text" style="width: 200px" id="plate_noA" class="form-control">
                                                                            <option value="null">All Vehicles</option>
                                                                            <?php foreach ($vehicles as $key => $value) { ?>
                                                                            <option value="<?php echo $value->plate_no ; ?>"><?php echo $value->plate_no ; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Date</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-calendar fa fa-calendar'></i>
                                                                        </span>
                                                                        <input type="text" style="width: 200px" id="reportrangeA" class="form-control" value="" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-xs-3">
                                                <div style="margin-top:38px;">
                                                    <button class="btn btn-xs btn-primary" id="generateA">Generate Report</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="alert_table"></div>

                                    </div>

                                    <!-- Purchases -->
                                    <div class="tab-pane" id="purchases">

                                        <div class="row" id="controls">

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Plate No</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-car fa fa-car'></i>
                                                                        </span>
                                                                        <select type="text" style="width: 200px" id="plate_noP" class="form-control">
                                                                            <option value="null">All Vehicles</option>
                                                                            <?php foreach ($vehicles as $key => $value) { ?>
                                                                            <option value="<?php echo $value->plate_no ; ?>"><?php echo $value->plate_no ; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Date</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-calendar fa fa-calendar'></i>
                                                                        </span>
                                                                        <input type="text" style="width: 200px" id="reportrangeP" class="form-control" value="" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-xs-3">
                                                <h6><b>Filter By Product</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls'>
                                                                    <div class='input-prepend input-group'>
                                                                        <span class='add-on input-group-addon'>
                                                                            <i class='glyphicon glyphicon-shopping-cart fa fa-shopping-cart'></i>
                                                                        </span>
                                                                        <select type="text" style="width: 200px" id="productP" class="form-control">
                                                                            <option value="null">All Products</option>
                                                                            <?php foreach ($products as $key => $value) { ?>
                                                                            <option value="<?php echo $value->product_id ; ?>"><?php echo $value->product_name; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>

                                            <!--<div class="col-xs-3">
                                                <h6><b>Filter by Sum</b></h6>
                                                <div style="margin-bottom:20px;">
                                                    <form class='form-horizontal'>
                                                        <fieldset>
                                                            <div class='control-group'>
                                                                <div class='controls' style="margin-top: 8px;">
                                                                    <input id="sumP" type="checkbox">  &nbsp; Totals
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>-->

                                            <div class="col-xs-3">
                                                <div style="margin-top:38px;">
                                                    <button class="btn btn-xs btn-primary" id="generateP">Generate Report</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="purchases_table"></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/dataTables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/buttons.bootstrap.min.js')?>"></script>

<script src="<?php echo base_url('assets/dataTables/buttons.flash.min.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/buttons.print.min.js')?>"></script>

<script src="<?php echo base_url('assets/dataTables/jszip.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/jszip.min.js')?>"></script>

<script src="<?php echo base_url('assets/dataTables/pdfmake.min.js')?>"></script>
<script src="<?php echo base_url('assets/dataTables/vfs_fonts.js')?>"></script>

<script src="<?php echo base_url('assets/js/plugins/daterangepicker/daterangepicker.js')?>"></script>
<script src="<?php echo base_url('assets/js/plugins/daterangepicker/moment.min.js')?>"></script>

<script src="<?php echo base_url('assets/js/reports/script_general.js')?>"></script>
<script src="<?php echo base_url('assets/js/reports/script_mileage.js')?>"></script>
<script src="<?php echo base_url('assets/js/reports/script_alerts.js')?>"></script>
<script src="<?php echo base_url('assets/js/reports/script_purchase.js')?>"></script>
