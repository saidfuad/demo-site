$(function () {
    
    //get_data();
});

function get_data() {
    $.ajax({
          type: "GET",
          url: "admin/get_subscriptions_count", // This is the URL to the API
          data: { subs: 'subs' } // Passing a parameter to the API to specify number of days
        })
        .done(function( data ) {
          // When the response to the AJAX request comes back render the chart with new data
          serviceCosts(JSON.parse(data));
        })
        .fail(function() {
          // If there is no communication between the server, show an error
          alert( "error occured" );
        });
}

function serviceCosts(data) {
    alert(data);

    //var subs = [['No Service', 0]];
    var url_ = '';
    var res = '';

    

    var data = [
        { label: "Subscriptions", data: data }
    ];
    
    $.plot($("#service-costs"), data, {
        series: {
            bars: {
                show: true,
                barWidth: 12*24*60*60*350,
                lineWidth: 0,
                order: 1,
                fillColor: {
                    colors: [{
                        opacity: 1
                    }, {
                        opacity: 0.8
                    }]
                }
            }
        },
       
        yaxis: {
            axisLabel: 'Value',
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 13,
            axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            axisLabelPadding: 5
        },
        grid: {
            hoverable: true,
            borderWidth: 0
        },
        legend: {
            backgroundColor: "#fff",
            labelBoxBorderColor: "none"
        },
        colors: [infoColor ]
    });

    var previous_point = null;
    var previous_label = null;


}


function show_tooltip(x, y, contents, z) {
    $('<div id="bar_tooltip">' + contents + '</div>').css({
        top: y - 45,
        left: x - 28,
        'border-color': z,
    }).appendTo("body").fadeIn();
}

function get_month_name(month_timestamp) {
    var month_date = new Date(month_timestamp);
    var month_numeric = month_date.getMonth();
    var month_array = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var month_string = month_array[month_numeric];

    return month_string;
}