$(document).ready(function () {

    var startdate = null;
    var enddate = null;
    var plate = null;
    var sum = false;
    var product = null;

    var pTable = null;

    $('#plate_noP').on('change', function(){
        plate = $('#plate_noP').val();

        if(startdate !== null && enddate !== null){
            loadTableP(startdate, enddate, plate);
        }else{
            loadTableP(initialP[0], initialP[1], plate);
        }

    });

    $('#reportrangeP').daterangepicker({

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

    $('#reportrangeP').val(moment().subtract(14, 'days').format('DD-MM-YYYY') + " - " + moment().format('DD-MM-YYYY'));
    var initialP = $('#reportrangeP').val();
    initialP = initialP.split(" - ");

    $('#reportrangeP').on('apply.daterangepicker', function(ev, picker) {

        startdate = picker.startDate.format('YYYY-MM-DD');
        enddate = picker.endDate.format('YYYY-MM-DD');

        if(startdate !== null && enddate !== null){
            loadTableP(startdate, enddate, plate, product, sum);
        }else{
            loadTableP(initialP[0], initialP[1], plate, product, sum);
        }

    });

    $('#productP').on('change', function(){
        product = $('#productP').val();

        if(startdate !== null && enddate !== null){
            loadTableP(startdate, enddate, plate, product, sum);
        }else{
            loadTableP(initialP[0], initialP[1], plate, product, sum);
        }

    });

    $('#sumP').on('click', function(){

        sum = !!$(this).is(':checked');

        if(startdate !== null && enddate !== null){
            loadTableP(startdate, enddate, plate, product, sum);
        }else{
            loadTableP(initialP[0], initialP[1], plate, product, sum);
        }

    });

    /* :: Load Table */

    function loadTableP(startdate, enddate, plate, product, sum){

        /*if(pTable !== null){
            pTable.fnDestroy();
        }

        pTable = $('#purchases_table').dataTable({
            dom: 'Bfrtip',
            ajax: {
                "url": 'reports/get_purchase_reports' + '/' + startdate + '/' + enddate + '/' + plate + '/' + product + '/' + sum,
                "type": "POST",
                "contentType": "application/json; charset=utf-8",
                "dataType": "json",
                "dataSrc": function (json) {
                    var return_data = new Array();
                    for(var i=0;i< json.length; i++){
                        return_data.push({
                            'Date': json[i].order_date,
                            'Name': json[i].product_name,
                            'Qty': json[i].quantity,
                            'Total': json[i].total_price,
                            'Plate': json[i].plate_no,
                            'Model': json[i].model
                        })
                    }
                    return return_data;
                }
            },
            "columns": [
                {'data': 'Date'},
                {'data': 'Name'},
                {'data': 'Qty'},
                {'data': 'Total'},
                {'data': 'Plate'},
                {'data': 'Model'}
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Hawk | Purchase Reports',
                    orientation: 'portrait',
                    pageSize: 'A4'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Hawk | Purchase Reports',
                    message: 'PDF created on HAWK | ALWAYS WATCHING | 2016',
                    orientation: 'portrait',
                    pageSize: 'A4'
                }
            ]
        });*/

        var pReportDS = new kendo.data.DataSource({
            transport: {
                read: {
                    type: "POST",
                    dataType: "json",
                    url: 'reports/get_purchase_reports' + '/' + startdate + '/' + enddate + '/' + plate + '/' + product + '/' + sum
                }
            },
            schema: {
                model: {
                    fields: {
                        add_date: {type: "string"},
                        product_name: {type: "string"},
                        quantity: {type: "string"},
                        total_price: {type: "number"},
                        plate_no: {type: "string"},
                        model: {type: "string"}
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

        pTable = $("#purchases_table").kendoGrid({
            dataSource: pReportDS,
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
                    field: "product_name",
                    title: "Product Name"
                },
                {
                    field: "quantity",
                    title: "Qty."
                },
                {
                    field: "total_price",
                    title: "Total Price (Ksh)",
                    attributes: {style: "text-align:right;"},
                    format: "{0:#,##}"
                },
                {
                    field: "plate_no",
                    title: "Reg. No."
                },
                {
                    field: "model",
                    title: "Model"
                }
            ],
            toolbar: ["excel"],
            excel: {
                allPages: true,
                fileName: "Purchase Reports.xlsx"
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

    $('#generateP').on('click', function(){

        if(startdate !== null && enddate !== null){
            loadTableP(startdate, enddate, plate, sum);
        }else{
            loadTableP(initialP[0], initialP[1], plate, sum);
        }

    });


});
