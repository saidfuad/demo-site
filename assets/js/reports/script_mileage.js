$(document).ready(function () {

    var startdate = null;
    var enddate = null;
    var plate = null;
    var sum = false;

    var mTable = null;

    $('#plate_noM').on('change', function(){
        plate = $('#plate_noM').val();

        if(startdate !== null && enddate !== null){
            loadTableM(startdate, enddate, plate, sum);
        }else{
            loadTableM(initialM[0], initialM[1], plate, sum);
        }

    });

    $('#reportrangeM').daterangepicker({

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

    $('#reportrangeM').val(moment().subtract(14, 'days').format('DD-MM-YYYY') + " - " + moment().format('DD-MM-YYYY'));
    var initialM = $('#reportrangeM').val();
    initialM = initialM.split(" - ");

    $('#reportrangeM').on('apply.daterangepicker', function(ev, picker) {

        startdate = picker.startDate.format('YYYY-MM-DD');
        enddate = picker.endDate.format('YYYY-MM-DD');

        if(startdate !== null && enddate !== null){
            loadTableM(startdate, enddate, plate, sum);
        }else{
            loadTableM(initialM[0], initialM[1], plate, sum);
        }

    });

    $('#sumM').on('click', function(){

        sum = !!$(this).is(':checked');

        if(startdate !== null && enddate !== null){
            loadTableM(startdate, enddate, plate, sum);
        }else{
            loadTableM(initialM[0], initialM[1], plate, sum);
        }

    });

    /* :: Load Table */

    function loadTableM(startdate, enddate, plate, sum){

        /*if(mTable !== null){
            mTable.fnDestroy();
        }

        mTable = $('#mileage_table').dataTable({
            dom: 'Bfrtip',
            ajax: {
                "url": 'reports/get_mileage_reports' + '/' + startdate + '/' + enddate + '/' + plate + '/' + sum,
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
                            'Mileage': json[i].total_mileage
                        })
                    }
                    return return_data;
                }
            },
            "columns": [
                {'data': 'Date'},
                {'data': 'Plate'},
                {'data': 'Model'},
                {'data': 'Mileage'},
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Hawk | Mileage Reports',
                    orientation: 'portrait',
                    pageSize: 'A4'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Hawk | Mileage Reports',
                    message: 'PDF created on HAWK | ALWAYS WATCHING | 2016',
                    orientation: 'portrait',
                    pageSize: 'A4'
                }
            ]
        });*/

        var mReportDS = new kendo.data.DataSource({
            transport: {
                read: {
                    type: "POST",
                    dataType: "json",
                    url: 'reports/get_mileage_reports' + '/' + startdate + '/' + enddate + '/' + plate + '/' + sum
                }
            },
            schema: {
                model: {
                    fields: {
                        add_date: {type: "string"},
                        plate_no: {type: "string"},
                        model: {type: "string"},
                        total_mileage: {type: "number"}
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

        mTable = $("#mileage_table").kendoGrid({
            dataSource: mReportDS,
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
                    field: "total_mileage",
                    title: "Total Mileage (KM)",
                    attributes: {style: "text-align:right;"},
                    format: "{0:#,##}"
                }
            ],
            toolbar: ["excel"],
            excel: {
                allPages: true,
                fileName: "Mileage Reports.xlsx"
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

    $('#generateM').on('click', function(){

        if(startdate !== null && enddate !== null){
            loadTableM(startdate, enddate, plate, sum);
        }else{
            loadTableM(initialM[0], initialM[1], plate, sum);
        }

    });

});
