
$(document).ready(function () {
    var baseurl = $('#baseurl').attr('name');
    $.ajax({
        type: 'get', url: baseurl + '/graph-inquiry',
//        data: $('#save_all_price').serialize(),
        success: function (data) {
            new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'inquiry',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                    {day: data[1]['day'], pipe: data[1]['pipe'], structure: data[1]['structure']},
                    {day: data[2]['day'], pipe: data[2]['pipe'], structure: data[2]['structure']},
                    {day: data[3]['day'], pipe: data[3]['pipe'], structure: data[3]['structure']},
                    {day: data[4]['day'], pipe: data[4]['pipe'], structure: data[4]['structure']},
                    {day: data[5]['day'], pipe: data[5]['pipe'], structure: data[5]['structure']},
                    {day: data[6]['day'], pipe: data[6]['pipe'], structure: data[6]['structure']},
                    {day: data[7]['day'], pipe: data[7]['pipe'], structure: data[7]['structure']}
                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'day',
                xLabelAngle: 70,
                xLabelFormat: function (x) {
                    var IndexToMonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    var month = IndexToMonth[ x.getMonth() ];
                    var date = x.getDate();
                    return date + ' ' + month;
                },
                // A list of names of data record attributes that contain y-values.
                ykeys: ['pipe', 'structure'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Pipe', 'Structure'],
                lineColors: ["#3498DB", "#2ECC71"]
            });
        }
    });
});



$(document).ready(function () {
    var baseurl = $('#baseurl').attr('name');
    $.ajax({
        type: 'get', url: baseurl + '/graph-order',
//        data: $('#save_all_price').serialize(),
        success: function (data) {
            new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'order',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                    {day: data[1]['day'], pipe: data[1]['pipe'], structure: data[1]['structure']},
                    {day: data[2]['day'], pipe: data[2]['pipe'], structure: data[2]['structure']},
                    {day: data[3]['day'], pipe: data[3]['pipe'], structure: data[3]['structure']},
                    {day: data[4]['day'], pipe: data[4]['pipe'], structure: data[4]['structure']},
                    {day: data[5]['day'], pipe: data[5]['pipe'], structure: data[5]['structure']},
                    {day: data[6]['day'], pipe: data[6]['pipe'], structure: data[6]['structure']},
                    {day: data[7]['day'], pipe: data[7]['pipe'], structure: data[7]['structure']}

                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'day',
                xLabelAngle: 70,
                xLabelFormat: function (x) {
                    var IndexToMonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    var month = IndexToMonth[ x.getMonth() ];
                    var date = x.getDate();
                    return date + ' ' + month;
                },
                // A list of names of data record attributes that contain y-values.
                ykeys: ['pipe', 'structure'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Pipe', 'Structure'],
                lineColors: ["#3498DB", "#2ECC71"]
            });
        }
    });
});

$(document).ready(function () {
    var baseurl = $('#baseurl').attr('name');
    $.ajax({
        type: 'get', url: baseurl + '/graph-delivery-challan',
//        data: $('#save_all_price').serialize(),
        success: function (data) {
            new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'deliverychallan',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                    {day: data[1]['day'], pipe: data[1]['pipe'], structure: data[1]['structure']},
                    {day: data[2]['day'], pipe: data[2]['pipe'], structure: data[2]['structure']},
                    {day: data[3]['day'], pipe: data[3]['pipe'], structure: data[3]['structure']},
                    {day: data[4]['day'], pipe: data[4]['pipe'], structure: data[4]['structure']},
                    {day: data[5]['day'], pipe: data[5]['pipe'], structure: data[5]['structure']},
                    {day: data[6]['day'], pipe: data[6]['pipe'], structure: data[6]['structure']},
                    {day: data[7]['day'], pipe: data[7]['pipe'], structure: data[7]['structure']}

                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'day',
                xLabelAngle: 70,
                xLabelFormat: function (x) {
                    var IndexToMonth = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    var month = IndexToMonth[ x.getMonth() ];
                    var date = x.getDate();
                    return date + ' ' + month;
                },
                // A list of names of data record attributes that contain y-values.
                ykeys: ['pipe', 'structure'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Pipe', 'Structure'],
                lineColors: ["#3498DB", "#2ECC71"],
            });
        }
    });
});

