
<?php
$this->load->model('main/mdl_main');

// $vehicles = $this->mdl_main->fetch_report_vehicles($this->session->userdata('itms_company_id'));
// $dealers = $this->mdl_main->fetch_report_dealers();
// $owners = $this->mdl_main->fetch_report_owners();
// $reports = $this->mdl_main->fetch_report_types();
// $drivers = $this->mdl_main->fetch_drivers();
// $roles = $this->mdl_main->fetch_roles();
//$scheduled_reports = $this->mdl_main->fetch_scheduled_reports();
?>

<div class="panel-diagnostics" id="report-panel">
    <div class="panel-heading">
        <span><i class="fa fa-files-o"></i>&nbsp; Reports</span>
        <span class="close cx"><b>&times;</b></span>
    </div>
    <!--Reports Panel-->
    <div class="panel-body" style="padding-top:10px;">
        <div class="col-md-6">
            <ul class="nav nav-pills">
                <li class="active"><a data-toggle="tab" href="#tab-one" id="tab-one-title">Vehicles</a></li>
                <li><a data-toggle="pill" href="#tab-two" id="tab-two-title">Drivers</a></li>
                <!--<li><a data-toggle="pill" href="#menu2">Zones</a></li>
                <li><a data-toggle="pill" href="#menu3">Devices</a></li>-->
            </ul><!--<legend></legend>-->
            <div class="tab-content">
                <div id="tab-one" class="tab-pane fade in active">
                    <div class="panel panel-default panel-select">
                        <div class="form-group" style="border:2px solid #eee">
                            <select multiple class="multiselect" id="tab-one-data">
                                <?php foreach ($vehicles as $key => $vehicle) { ?>
                                    <option value="<?php echo $vehicle->asset_id; ?>"><?php echo $vehicle->assets_friendly_nm . ' - ' . $vehicle->assets_name; ?></option>
                            <select multiple class="multiselect" id="tab-one-data">
                                <?php foreach ($v as $key => $vehicle) { ?>
                                    <option value="<?php echo $vehicle->vehicle_id; ?>"><?php echo $vehicle->plate_no; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="tab-two" class="tab-pane fade">
                    <div class="panel panel-default panel-select">
                        <div class="form-group" style="border:2px solid #eee">
                           <!--  <select multiple class="multiselect" id="tab-two-data" min="1">
                                <?php foreach ($drivers as $key => $driver) { ?>
                                    <option value="<?php echo $driver->personnel_id; ?>"><?php echo $driver->fname . ' ' . $driver->lname; ?></option>
                                <?php } ?>
                            </select> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 form-control-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm input-sm" id="report-name" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-3 form-control-label">Type</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm input-sm" id="report-id">
                        <?php foreach ($reports as $key => $report) { ?>
                            <option value="<?php echo $report->report_id; ?>"><?php echo $report->report_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 form-control-label">Format</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm input-sm" id="format">
                        <option id="pdf_option">PDF</option>
                        <option id="excel_option">EXCEL</option>
                        <option id="html_option">HTML</option>
                    </select>
                </div>
            </div>

            <!--<div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 form-control-label">Date:</label>
                <div class="col-sm-9">
                    <input class="form-control" id="datepicker" value="1/1/2016 12:00 AM" type="text"/>
                </div>
            </div>-->
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 form-control-label">Period:</label>
                <div class="col-sm-9">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span id='span-date'></span> <b class="caret"></b>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">

                </div>
                <div class="col-sm-9">
                    <button class="btn btn-primary pull-right" id="btn-gen-report">Generate Report &nbsp;<i class="fa fa-file-pdf-o"></i></button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <legend><h5><strong>Schedule</strong></h5></legend>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-transform:lowercase">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="checkbox" id="daily"  name="daily">
                            <label for="inlineRadio2"> Daily </label>
                        </div>
                        <div class="col-md-2">
                            <input type="checkbox" id="weekly" name="weekly">
                            <label for="inlineRadio3"> Weekly </label>
                        </div>
                        <div class="col-md-1" align="right">
                            <label for="inlineRadio2"> Email: </label>
                        </div>  
                        <div class="col-md-5">
                            <input type="email" placeholder="Enter email to schedule reports" class="form-control input-sm" id="report-email" value="" name="report_email" >
                        </div>    
                    </div>
                </div>
                <style>
                    /*
Force table width to 100%
                    */
                    table.table-fixedheader {
                        width: 100%;   
                    }
                    /*
                    Set table elements to block mode.  (Normally they are inline).
                    This allows a responsive table, such as one where columns can be stacked
                    if the display is narrow.
                    */
                    table.table-fixedheader, table.table-fixedheader>thead, table.table-fixedheader>tbody, table.table-fixedheader>thead>tr, table.table-fixedheader>tbody>tr, table.table-fixedheader>thead>tr>th, table.table-fixedheader>tbody>td {
                        display: block;
                    }
                    table.table-fixedheader>thead>tr:after, table.table-fixedheader>tbody>tr:after {
                        content:' ';
                        display: block;
                        visibility: hidden;
                        clear: both;
                    }
                    /*
                    When scrolling the table, actually it is only the tbody portion of the
                    table that scrolls (not the entire table: we want the thead to remain
                    fixed).  We must specify an explicit height for the tbody.  We include
                    100px as a default, but it can be overridden elsewhere.
                    
                    Also, we force the scrollbar to always be displayed so that the usable
                    width for the table contents doesn't change (such as becoming narrower
                    when a scrollbar is visible and wider when it is not).
                    */
                    table.table-fixedheader>tbody {
                        overflow-y: scroll;
                        height: 90px;

                    }
                    /*
                    We really don't want to scroll the thead contents, but we want to force
                    a scrollbar to be displayed anyway so that the usable width of the thead
                    will exactly match the tbody.
                    */
                    table.table-fixedheader>thead {
                        overflow-y: scroll;    
                    }
                    /*
                    For browsers that support it (webkit), we set the background color of
                    the unneeded scrollbar in the thead to make it invisible.  (Setting
                    visiblity: hidden defeats the purpose, as this alters the usable width
                    of the thead.)
                    */
                    table.table-fixedheader>thead::-webkit-scrollbar {
                        background-color: inherit;
                    }


                    table.table-fixedheader>thead>tr>th:after, table.table-fixedheader>tbody>tr>td:after {
                        content:' ';
                        display: table-cell;
                        visibility: hidden;
                        clear: both;
                    }

                    /*
                    We want to set <th> and <td> elements to float left.
                    We also must explicitly set the width for each column (both for the <th>
                    and the <td>).  We set to 20% here a default placeholder, but it can be
                    overridden elsewhere.
                    */

                    table.table-fixedheader>thead tr th, table.table-fixedheader>tbody tr td {
                        float: left;    
                        word-wrap:break-word;  
                        width: 14.28%;
                    }

                </style>
                <div class="panel-body" style="height:150px;">
                    <?php if (sizeof($scheduled_reports)) { ?>
                        <table class="table table-striped table-hover table-fixedheader">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>TYPE</th>
                                    <th>FORMAT</th>
                                    <th>TAB ONE</th>
                                    <th>TAB TWO</th>
                                    <th>SCHEDULE</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                foreach ($scheduled_reports as $key => $report) {
                                    $tab_one_count = (strlen($report->tab_one_ids) > 1) ? sizeof(explode(",", $report->tab_one_ids)) : 0;
                                    $tab_two_count = (strlen($report->tab_two_ids) > 1) ? sizeof(explode(",", $report->tab_two_ids)) : 0;
                                    ?>
                                    <tr class="" style="" role="row" onclick="loadSettings(this.id)" id='<?= $report->id; ?>'>
                                        <td><?= $report->report_name; ?></td>
                                        <td><?= $report->report_type_name; ?></td>
                                        <td><?= $report->format; ?></td>
                                        <td><?= $tab_one_count; ?></td>
                                        <td><?= $tab_two_count; ?></td>
                                        <td>
                                            <?php
                                            if ($report->daily || $report->weekly) {
                                                echo "<span class='fa fa-check fa-lg'></span>";
                                            } else {
                                                echo "<span class='fa fa-times fa-lg'></span>";
                                            }
                                            ?>
                                        </td>
                                        <td><a onclick="deleteSchedule(<?= $report->id; ?>)"><span class="fa fa-trash fa-lg"></span></a></td>
                                    </tr> 
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table> 
                    <?php } else { ?>
                        <div class="alert alert-info">No scheduled Reports Available</div>   
                    <?php } ?> 

                </div>
            </div>

        </div>
    </div>
    <div class="panel-footer" align="right">
        <div class="row">
            <div class='col-md-8'></div>
            <div class='col-md-2'>
                <button class="btn btn-primary pull-left" id="btn-close-dialog">Cancel</button>
            </div>
            <div class='col-md-2'>
                <button class="btn btn-primary  pull-right" id="btn-print-report" data-toggle="modal" data-target="#processing-modal">Save Schedule &nbsp;<i class="fa fa-floppy-o"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- Static Modal -->
<link href="<?= base_url("assets/css/styles/progress_dialog.css") ?>" rel='stylesheet' type='text/css' />
<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true" style="z-index: 3001;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <img src="http://www.travislayne.com/images/loading.gif" class="icon" />
                    <h4>Processing... <button type="button" class="close" style="float: none;" data-dismiss="modal" aria-hidden="true">×</button></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    /*function deleteSchedule(id) {
     $.ajax({
     type: "GET",
     data: {id: id},
     url: "<?= site_url() ?>/mpdf_main/delete_report_schedule",
     success: function (response) {
     }
     });
     }
     
     function loadSettings(id) {
     $("#processing-modal").modal("show");
     $.ajax({
     type: "GET",
     cache: false,
     data: {id: id},
     url: "<?php echo base_url() ?>index.php/mpdf_main/fetch_scheduled_report_by_id",
     success: function (response) {
     if (response != "null") {
     var data = JSON.parse(response);
     //update schedule list
     $("#report-name").val(data.report_name);
     $("#span-date").html(data.start_period + " - " + data.end_period);
     $("#report-email").val(data.email);
     if (data.format === "PDF") {
     
     $('#format option[id=pdf_option]').attr('selected', 'selected');
     } else if (data.format === "EXCEL") {
     $('#format option[id=excel_option]').attr('selected', 'selected');
     } else if (data.format === "HTML") {
     $('#format option[id=html_option]').attr('selected', 'selected');
     }
     $('#report-id').val(data.report_type_id);
     var tab_one = data.tab_one_ids.split(",");
     var tab_two = data.tab_two_ids.split(",");
     $('#tab-one-data').val(tab_one);
     $('#tab-two-data').val(tab_two);
     var daily = (data.daily === "0") ? false : true;
     var weekly = (data.weekly === "0") ? false : true;
     $('#daily').prop('checked', daily);
     $('#weekly').prop('checked', weekly);
     }
     updateTable();
     $("#processing-modal").modal("hide");
     }
     });
     
     }*/
    /*$(function () {
     //$("#tab-two-title").hide();
     $("#btn-close-dialog").click(function () {
     $(".overshadow").hide();
     $(".panel-diagnostics").hide();
     });
     var report_id;
     $('#tab-one-data').on('change', function () {
     if (this.selectedOptions.length < 2 && report_id === "12") {
     $(this).find(':selected').addClass('selected');
     $(this).find(':not(:selected)').removeClass('selected');
     } else if (report_id === "12") {
     $(this)
     .find(':selected:not(.selected)')
     .prop('selected', false);
     }
     });
     
     $("#report-id").change(function () {
     report_id = $('#report-id').val();
     //alert(report_id);
     function excelPdf() {
     $("#html_option").hide();
     $("#pdf_option").show();
     $("#excel_option").show();
     }
     switch (report_id) {
     case '1': //overspeeding
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     loadDrivers('tab-two', 'Drivers');
     break;
     case '2': //landmarks in out
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     //loadLandMarks()
     break;
     case '3': //alerts
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     loadDrivers('tab-two', 'Drivers');
     //$("#tab-two-title").hide();
     break;
     case '4': //trips
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     break;
     case '5': //vehicle summary
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     break;
     case '6': //owners
     excelPdf();
     loadOwners('tab-one', 'Owners');
     $("#tab-two-title").hide();
     break;
     case '7': //Distance
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     break;
     case '8': //Route infringement
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     break;
     case '9': //TPMS
     excelPdf();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     break;
     case '10': //Personnel
     excelPdf();
     loadRoles('tab-one', 'Roles');
     $("#tab-two-title").hide();
     break;
     case '11': //Dealers
     excelPdf();
     loadDealers('tab-one', 'Dealers');
     $("#tab-two-title").hide();
     break;
     case '12': //ACC ignition graph
     $("#html_option").show();
     $("#pdf_option").hide();
     $("#excel_option").hide();
     loadVehicles('tab-one', 'Vehicles');
     $("#tab-two-title").hide();
     //select default
     $('#tab-one-data option:first-child').attr("selected", "selected");
     break;
     }
     });
     
     function attachToTab(tab, title, options) {
     $('#' + tab + '-data').html(options);
     $('#' + tab + '-title').html(title);
     $('#' + tab + '-title').show();
     }
     
     function loadOwners(tab, title) {
     
     var data = JSON.parse('<?= json_encode($owners) ?>');
     var options = "";
     for (i = 0; i < data.length; i++) {
     options += '<option value="' + data[i].owner_id + '">' + data[i].owner_name + '</option>';
     }
     attachToTab(tab, title, options);
     }
     
     function loadVehicles(tab, title) {
     var data = JSON.parse('<?= json_encode($vehicles) ?>');
     var options = "";
     for (i = 0; i < data.length; i++) {
     options += '<option value="' + data[i].asset_id + '">' + data[i].assets_friendly_nm + ' - ' + data[i].assets_name + '</option>';
     }
     attachToTab(tab, title, options);
     }
     
     function loadDealers(tab, title) {
     
     var data = JSON.parse('<?= json_encode($dealers) ?>');
     var options = "";
     for (i = 0; i < data.length; i++) {
     options += '<option value="' + data[i].dealer_id + '">' + data[i].dealer_name + '</option>';
     }
     attachToTab(tab, title, options);
     }
     
     function loadRoles(tab, title) {
     
     var data = JSON.parse('<?= json_encode($roles) ?>');
     var options = "";
     for (i = 0; i < data.length; i++) {
     options += '<option value="' + data[i].role_id + '">' + data[i].role_name + '</option>';
     }
     attachToTab(tab, title, options);
     }
     
     function loadPersonnel(tab, title) {
     
     var data = JSON.parse('<?//= json_encode($personnel) ?>');
     var options = "";
     for (i = 0; i < data.length; i++) {
     options += '<option value="' + data[i].personel_id + '">' + data[i].role_name + '</option>';
     }
     attachToTab(tab, title, options);
     
     }
     
     function loadDrivers(tab, title) {
     
     var data = JSON.parse('<?= json_encode($drivers) ?>');
     var options = "";
     for (i = 0; i < data.length; i++) {
     options += '<option value="' + data[i].personnel_id + '">' + data[i].fname + ' ' + data[i].lname + '</option>';
     }
     attachToTab(tab, title, options);
     }
     
     var start_period, end_period;
     function cb(start, end) {
     $('#reportrange span').html(start.format('DD/MM/YYYY H:mm') + ' - ' + end.format('DD/MM/YYYY H:mm'));
     start_period = start.format('YYYY-MM-DD H:mm');
     end_period = end.format('YYYY-MM-DD H:mm');
     }
     cb(moment().subtract(29, 'days'), moment());
     $('#reportrange').daterangepicker({
     ranges: {
     'Today': [moment().startOf('day'), moment()],
     'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
     'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
     'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
     'This Month': [moment().startOf('month'), moment().endOf('month')],
     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
     },
     timePicker: true,
     timePickerIncrement: 30,
     locale: {
     format: 'DD/MM/YYYY H:mm'
     }
     }, cb);
     $("#datepicker").daterangepicker({
     singleDatePicker: true,
     timePicker: true,
     timePickerIncrement: 30,
     locale: {
     format: 'MM/DD/YYYY h:mm'
     }
     });
     
     function isEmail(email) {
     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
     return regex.test(email);
     }
     
     function post_to_url(url, params) {
     var form = document.createElement('form');
     form.action = url;
     form.method = 'POST';
     form.setAttribute("target", "_blank");
     
     for (var i in params) {
     if (params.hasOwnProperty(i)) {
     var input = document.createElement('input');
     input.type = 'hidden';
     input.name = i;
     input.value = params[i];
     form.appendChild(input);
     }
     }
     form.submit();
     }
     
     $('#btn-print-report,#btn-gen-report').on('click', function () {
     var clicked_button = (this.id);
     swal({
     title: 'Generate & Schedule Report',
     text: "Do you want to continue?",
     type: 'info',
     showCancelButton: true,
     confirmButtonColor: '#3085d6',
     cancelButtonColor: '#d33',
     confirmButtonText: 'Continue!',
     closeOnConfirm: true
     },
     function () {
     $("#processing-modal").modal("show");
     //alert();
     var report_id = $('#report-id').val();
     var report_name = $('#report-name').val();
     var report_email = $('#report-email').val();
     var format = $('#format').val();
     var tab_one_ids = $('#tab-one-data').val();
     var tab_two_ids = $('#tab-two-data').val();
     var schedule = $("input[name=schedule]:checked").val();
     var download = false;
     if (clicked_button === "btn-gen-report") {
     download = true;
     
     if (report_id === '12') {
     //ajax request
     $.ajax({
     type: "GET",
     cache: false,
     data: {report_id: report_id, report_name: report_name, report_email: report_email, format: format,
     tab_one_ids: tab_one_ids, tab_two_ids: tab_two_ids, start_period: start_period, end_period: end_period, schedule: schedule, download: download, weekly: weekly, daily: daily},
     url: "<?php echo base_url() ?>index.php/mpdf_main/print_report",
     success: function (response) {
     
     response = JSON.parse(response);
     console.log(JSON.stringify(response));
     post_to_url('<?= site_url() ?>' + '/reports/ignition_graph', response);
     //window.open(response, '_blank');
     }
     });
     } else {
     window.open("<?php echo site_url() ?>/mpdf_main/print_report?report_id=" + report_id
     + "&report_name=" + report_name
     + "&report_email=" + report_email
     + "&format=" + format
     + "&tab_one_ids=" + tab_one_ids
     + "&tab_two_ids=" + tab_two_ids
     + "&start_period=" + start_period
     + "&end_period=" + end_period
     + "&schedule=" + schedule
     + "&download=" + download, '_blank');
     }
     } else if (clicked_button === "btn-print-report") {
     var daily = $('#daily').prop("checked");
     var weekly = $('#weekly').prop("checked");
     if (report_name === "") {
     swal({title: "Reports", text: 'Name cannot be empty', type: 'info'});
     $("#processing-modal").modal("hide");
     return false;
     }
     
     if (tab_one_ids === "null" && tab_two_ids === "null") {
     swal({title: "Reports", text: 'Atleast one object in tab has to be selcted', type: 'info'});
     $("#processing-modal").modal("hide");
     return false;
     }
     
     if ((daily === true || weekly === true) && (report_email.trim().length === 0)) {
     swal({title: 'Info', text: 'Enter email first', type: 'info', confirmButtonText: "Close"});
     $("#processing-modal").modal("hide");
     return false;
     }
     if ((daily === true || weekly === true) && (!isEmail(report_email))) {
     swal({title: 'Info', text: 'Enter valid email', type: 'info', confirmButtonText: "Close"});
     $("#processing-modal").modal("hide");
     return false;
     }
     $.ajax({
     type: "GET",
     cache: false,
     data: {report_id: report_id, report_name: report_name, report_email: report_email, format: format,
     tab_one_ids: tab_one_ids, tab_two_ids: tab_two_ids, start_period: start_period, end_period: end_period,
     schedule: schedule, download: download, weekly: weekly, daily: daily},
     url: "<?php echo base_url() ?>index.php/mpdf_main/print_report",
     success: function (response) {
     //update table
     updateTable();
     }
     });
     }
     $("#processing-modal").modal("hide");
     });
     
     return false;
     });
     });
     */
    /*function updateTable() {
     $.ajax({
     type: "GET",
     url: "<?php echo base_url() ?>index.php/mpdf_main/fetch_scheduled_reports",
     success: function (response) {
     $("#processing-modal").modal("hide");
     
     var data = JSON.parse(response);
     
     var tbody = '';
     for (var i = 0; i < data.length; i++) {
     var tab_one_count = ((data[i].tab_one_ids.length) > 1) ? (data[i].tab_one_ids.split(",").length) : 0;
     var tab_two_count = ((data[i].tab_two_ids.length) > 1) ? (data[i].tab_two_ids.split(",").length) : 0;
     
     tbody += '<tr class="" style="" role="row" onclick="loadSettings(this.id)" id="' + data[i].id + '">' +
     '<td>' + data[i].report_name + '</td>' +
     '<td>' + data[i].report_type_name + '</td>' +
     '<td>' + data[i].format + '</td>' +
     '<td>' + tab_one_count + '</td>' +
     '<td>' + tab_two_count + '</td>' +
     '<td>';
     
     var daily = (data[i].daily === '1') ? true : false;
     var weekly = (data[i].weekly === '1') ? true : false;
     
     if (daily || weekly) {
     tbody += "<span class='fa fa-check fa-lg'></span>";
     } else {
     tbody += "<span class='fa fa-times fa-lg'></span>";
     }
     
     tbody += '</td>' +
     '<td><a onclick=" deleteSchedule(' + data[i].id + ')"><span class="fa fa-trash fa-lg"></span></a></td>' +
     '</tr>';
     }
     $("#table-body").html(tbody);
     }
     });
     }*/
</script>
