$(document).ready(function () {

    var startdate = null;
    var enddate = null;
    var hTable = null;

    var vehicle_id = $('#vehicle-id').val();

    $('#reportrangeH').daterangepicker({

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

    $('#reportrangeH').val(moment().subtract(14, 'days').format('DD-MM-YYYY') + " - " + moment().format('DD-MM-YYYY'));
    var initialG = $('#reportrangeH').val();
    initialG = initialG.split(" - ");

    $('#reportrangeH').on('apply.daterangepicker', function (ev, picker) {

        startdate = picker.startDate.format('YYYY-MM-DD');
        enddate = picker.endDate.format('YYYY-MM-DD');

        if (startdate !== null & enddate !== null) {
            loadTableH(startdate, enddate, vehicle_id);
        } else {
            loadTableH(initialG[0], initialG[1], vehicle_id);
        }

    });

    /* :: Load Table */
    function loadTableH(startdate, enddate, vehicle_id) {

        if (hTable !== null) {
            hTable.fnDestroy();
        }

        hTable = $('#history-tbl').dataTable({
            dom: 'Bfrtip',
            ajax: {
                "url": '../get_history' + '/' + vehicle_id + '/' + startdate + '/' + enddate,
                "type": "POST",
                "contentType": "application/json; charset=utf-8",
                "dataType": "json",
                "dataSrc": function (json) {
                    var return_data = [];
                    for (var i = 0; i < json.length; i++) {
                        return_data.push({
                            'Plate': json[i].plate_no,
                            'StartTime': json[i].start_time,
                            'StartAddress': json[i].start_address,
                            'StopTime': json[i].stop_time,
                            'StopAddress': json[i].stop_address,
                            'Distance': json[i].distance
                        })
                    }
                    return return_data;
                }
            },
            "columns": [
                {'data': 'Plate'},
                {'data': 'StartTime'},
                {'data': 'StartAddress'},
                {'data': 'StopTime'},
                {'data': 'StopAddress'},
                {'data': 'Distance'},
                {
                    'data': function () {
                        return "<a data-original-title='View Map' class='btn btn-primary btn-xs' href='" + "../gps_history/history/"
                            + vehicle_id + "/"
                            + startdate + "/"
                            + enddate + "'>"
                            + "View History</a>";
                    }
                }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Hawk | History Reports',
                    orientation: 'portrait',
                    pageSize: 'A4'
                }
                /*,
                {
                    extend: 'pdfHtml5',
                    title: 'Hawk | History Reports',
                    message: 'PDF created on HAWK | ALWAYS WATCHING | 2016',
                    orientation: 'portrait',
                    pageSize: 'A4'
                }*/
            ]

        });
    }

    $('#generateH').on('click', function () {

        if (startdate !== null && enddate !== null) {
            loadTableH(startdate, enddate, vehicle_id);
        } else {
            loadTableH(initialG[0], initialG[1], vehicle_id);
        }

    });

});