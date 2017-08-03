$(document).ready(function () {

    var startdate = null;
    var enddate = null;
    var plate = null;
    var sum = false;

    var aTable = null;

    $('#plate_noA').on('change', function(){
        plate = $('#plate_noA').val();

        if(startdate !== null && enddate !== null){
            loadTableA(startdate, enddate, plate);
        }else{
            loadTableA(initialA[0], initialA[1], plate);
        }

    });

    $('#reportrangeA').daterangepicker({

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

    $('#reportrangeA').val(moment().subtract(14, 'days').format('DD-MM-YYYY') + " - " + moment().format('DD-MM-YYYY'));
    var initialA = $('#reportrangeA').val();
    initialA = initialA.split(" - ");

    $('#reportrangeA').on('apply.daterangepicker', function(ev, picker) {

        startdate = picker.startDate.format('YYYY-MM-DD');
        enddate = picker.endDate.format('YYYY-MM-DD');

        if(startdate !== null && enddate !== null){
            loadTableA(startdate, enddate, plate);
        }else{
            loadTableA(initialA[0], initialA[1], plate);
        }

    });

    /* :: Load Table */

    function loadTableA(startdate, enddate, plate){

        /*if(aTable !== null){
            aTable.fnDestroy();
        }

        aTable = $('#alert_table').dataTable({
            dom: 'Bfrtip',
            ajax: {
                "url": 'reports/get_alert_reports' + '/' + startdate + '/' + enddate + '/' + plate,
                "type": "POST",
                "contentType": "application/json; charset=utf-8",
                "dataType": "json",
                "dataSrc": function (json) {
                    var return_data = [];
                    for(var i=0;i< json.length; i++){
                        return_data.push({
                            'Date': json[i].alert_date,
                            'Type': json[i].alert_type,
                            'Location': json[i].alert_location,
                            'Plate': json[i].plate_no,
                            'Model': json[i].model,
                            'Status': json[i].alert_status
                        })
                    }
                    return return_data;
                }
            },
            "columns": [
                {'data': 'Date'},
                {'data': 'Type'},
                {'data': 'Location'},
                {'data': 'Plate'},
                {'data': 'Model'},
                {'data': 'Status'}
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Hawk | Alert Reports',
                    orientation: 'portrait',
                    pageSize: 'A4'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Hawk | Alert Reports',
                    message: 'PDF created on HAWK | ALWAYS WATCHING | 2016',
                    orientation: 'portrait',
                    pageSize: 'A4'
                }
            ]
        });*/

        var aReportDS = new kendo.data.DataSource({
            transport: {
                read: {
                    type: "POST",
                    dataType: "json",
                    url: 'reports/get_alert_reports' + '/' + startdate + '/' + enddate + '/' + plate
                }
            },
            schema: {
                model: {
                    fields: {
                        add_date: {type: "string"},
                        plate_no: {type: "string"},
                        alert_type: {type: "string"},
                        alert_location: {type: "string"},
                        status: {type: "string"}
                    }
                }
            },
            pageSize: 10,
            group: [
             {field: "plate_no"},
             ],
            /*aggregate: [
             {field: "no_trips", aggregate: "sum"},
             {field: "revenue", aggregate: "sum"}
             ]*/
        });

        aTable = $("#alert_table").kendoGrid({
            dataSource: aReportDS,
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
                    title: "Reg No.",
                    hidden: true
                },
                {
                    field: "alert_type",
                    title: "Alert Type"
                },
                {
                    field: "alert_location",
                    title: "Location"
                },
                {
                    field: "status",
                    title: "Status"
                }
            ],
            toolbar: ["excel"],
            excel: {
                allPages: true,
                fileName: "Alert Reports.xlsx"
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

    $('#generateA').on('click', function(){

        if(startdate !== null && enddate !== null){
            loadTableA(startdate, enddate, plate);
        }else{
            loadTableA(initialA[0], initialA[1], plate);
        }

    });

});
