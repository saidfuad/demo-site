$(document).ready(function () {

    var startdate = null;
    var enddate = null;
    var plate = null;
    var sum = false;

    var gTable = null;

    $('#plate_noG').on('change', function(){
            plate = $('#plate_noG').val();

            if(startdate !== null && enddate !== null){
                loadTableG(startdate, enddate, plate, sum);
            }else{
                loadTableG(initialG[0], initialG[1], plate, sum);
            }

        });

    $('#reportrangeG').daterangepicker({

        startDate: moment().subtract(24, 'hours'),
        endDate: moment(),
        minDate: moment().subtract(14, 'days'),
        maxDate: moment(),
        format: 'DD/MM/YYYY'

    }, function (start, end, label) {
        var s = moment(start.toISOString());
        var e = moment(end.toISOString());
        startdate = s.format('YYYY-MM-DD');
        enddate = e.format('YYYY-MM-DD');
    });

    $('#reportrangeG').val(moment().subtract(14, 'days').format('DD-MM-YYYY') + " - " + moment().format('DD-MM-YYYY'));
    var initialG = $('#reportrangeG').val();
    initialG = initialG.split(" - ");

    $('#reportrangeG').on('apply.daterangepicker', function(ev, picker) {

        startdate = picker.startDate.format('YYYY-MM-DD');
        enddate = picker.endDate.format('YYYY-MM-DD');

        if(startdate !== null && enddate !== null){
            loadTableG(startdate, enddate, plate, sum);
        }else{
            loadTableG(initialG[0], initialG[1], plate, sum);
        }

    });

    $('#sumG').on('click', function(){

        sum = !!$(this).is(':checked');

        if(startdate !== null && enddate !== null){
            loadTableG(startdate, enddate, plate, sum);
        }else{
            loadTableG(initialG[0], initialG[1], plate, sum);
        }

    });

    /* :: Load Table */
    function loadTableG(startdate, enddate, plate, sum){

        /*if(gTable !== null){
            gTable.fnDestroy();
        }

        gTable = $('#general_table').dataTable({
            dom: 'Bfrtip',
            ajax: {
                "url": 'reports/get_general_reports' + '/' + startdate + '/' + enddate + '/' + plate + '/' + sum,
                "type": "POST",
                "contentType": "application/json; charset=utf-8",
                "dataType": "json",
                "dataSrc": function (json) {
                    var return_data = [];
                    for(var i=0;i< json.length; i++){
                        return_data.push({
                            'Date': json[i].add_date,
                            'Plate': json[i].plate_no,
                            'Model': json[i].model,
                            'On': json[i].ignition_off,
                            'Off': json[i].ignition_on,
                            'Mileage': json[i].total_mileage,
                            'MaxSpeed': json[i].max_speed
                        })
                    }
                    return return_data;
                }
            },
            "columns": [
                {'data': 'Date'},
                {'data': 'Plate'},
                {'data': 'Model'},
                {'data': 'On'},
                {'data': 'Off'},
                {'data': 'Mileage'},
                {'data': 'MaxSpeed'}
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Hawk | General Reports',
                    orientation: 'portrait',
                    pageSize: 'A4'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Hawk | General Reports',
                    message: 'PDF created on HAWK | ALWAYS WATCHING | 2016',
                    orientation: 'portrait',
                    pageSize: 'A4'
                }
            ]
        });*/

        var gReportDS = new kendo.data.DataSource({
            transport: {
                read: {
                    type: "POST",
                    dataType: "json",
                    url: 'reports/get_general_reports' + '/' + startdate + '/' + enddate + '/' + plate + '/' + sum
                }
            },
            schema: {
                model: {
                    fields: {
                        add_date: {type: "string"},
                        plate_no: {type: "string"},
                        model: {type: "string"},
                        ignition_on: {type: "number"},
                        ignition_off: {type: "number"},
                        total_mileage: {type: "number"},
                        max_speed: {type: "number"}
                    }
                }
            },
            pageSize: 10,
            /*group: [
                {field: "month"},
                {field: "month_name"},
            ],*/
            /*aggregate: [
                {field: "no_trips", aggregate: "sum"},
                {field: "revenue", aggregate: "sum"}
            ]*/
        });

        gTable = $("#general_table").kendoGrid({
            dataSource: gReportDS,
            filterable: false,
            groupable: false,
            scrollable: false,
            sortable: false,
            pageable: true,
            columns: [
                {
                    field: "add_date",
                    title: "Date",
                    sortable: {
                        initialDirection: "asc"
                    }
                },
                {
                    field: "plate_no",
                    title: "Reg. No."
                },
                {
                    field: "model",
                    title: "Model"
                },
                {
                    field: "ignition_on",
                    title: "Days On"
                },
                {
                    field: "ignition_off",
                    title: "Days Off"
                },
                {
                    field: "total_mileage",
                    title: "Total Mileage (KM)",
                    attributes: {style: "text-align:right;"},
                    format: "{0:#,##}"
                },
                {
                    field: "max_speed",
                    title: "Max Speed (KM/H)",
                    attributes: {style: "text-align:right;"},
                    format: "{0:#,##}"
                }
            ],
            toolbar: ["excel"],
            excel: {
                allPages: true,
                fileName: "General Reports.xlsx"
            },
            /*pdf: {
             fileName: "General Reports.pdf",
             allPages: true,
             avoidLinks: true,
             paperSize: "A4",
             margin: {top: "2cm", left: "1cm", right: "1cm", bottom: "1cm"},
             landscape: true,
             repeatHeaders: true,
             template: $("#pdf-page-template").html(),
             scale: 0.8
             }*/
        });
    }

    $('#generateG').on('click', function(){

        if(startdate !== null && enddate !== null){
            loadTableG(startdate, enddate, plate, sum);
        }else{
            loadTableG(initialG[0], initialG[1], plate, sum);
        }

    });

});
