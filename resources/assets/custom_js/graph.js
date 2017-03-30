
new Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'inquiry',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    data: [
        {day: inquiry_stats[1]['day'], pipe: inquiry_stats[1]['pipe'], structure: inquiry_stats[1]['structure']},
        {day: inquiry_stats[2]['day'], pipe: inquiry_stats[2]['pipe'], structure: inquiry_stats[2]['structure']},
        {day: inquiry_stats[3]['day'], pipe: inquiry_stats[3]['pipe'], structure: inquiry_stats[3]['structure']},
        {day: inquiry_stats[4]['day'], pipe: inquiry_stats[4]['pipe'], structure: inquiry_stats[4]['structure']},
        {day: inquiry_stats[5]['day'], pipe: inquiry_stats[5]['pipe'], structure: inquiry_stats[5]['structure']},
        {day: inquiry_stats[6]['day'], pipe: inquiry_stats[6]['pipe'], structure: inquiry_stats[6]['structure']},
        {day: inquiry_stats[7]['day'], pipe: inquiry_stats[7]['pipe'], structure: inquiry_stats[7]['structure']}
    ],
    // The name of the data record attribute that contains x-values.
    xkey: 'day',
    xLabelAngle: 70,
    xLabelFormat: function (x) {
            var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
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

new Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'order',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    data: [
        {day: order_stats[1]['day'], pipe: order_stats[1]['pipe'], structure: order_stats[1]['structure']},
        {day: order_stats[2]['day'], pipe: order_stats[2]['pipe'], structure: order_stats[2]['structure']},
        {day: order_stats[3]['day'], pipe: order_stats[3]['pipe'], structure: order_stats[3]['structure']},
        {day: order_stats[4]['day'], pipe: order_stats[4]['pipe'], structure: order_stats[4]['structure']},
        {day: order_stats[5]['day'], pipe: order_stats[5]['pipe'], structure: order_stats[5]['structure']},
        {day: order_stats[6]['day'], pipe: order_stats[6]['pipe'], structure: order_stats[6]['structure']},
        {day: order_stats[7]['day'], pipe: order_stats[7]['pipe'], structure: order_stats[7]['structure']}

    ],
    // The name of the data record attribute that contains x-values.
    xkey: 'day',
    xLabelAngle: 70,
    xLabelFormat: function (x) {
            var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
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

new Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'deliverychallan',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    data: [
        {day: delivery_challan_stats[1]['day'], pipe: delivery_challan_stats[1]['pipe'], structure: delivery_challan_stats[1]['structure']},
        {day: delivery_challan_stats[2]['day'], pipe: delivery_challan_stats[2]['pipe'], structure: delivery_challan_stats[2]['structure']},
        {day: delivery_challan_stats[3]['day'], pipe: delivery_challan_stats[3]['pipe'], structure: delivery_challan_stats[3]['structure']},
        {day: delivery_challan_stats[4]['day'], pipe: delivery_challan_stats[4]['pipe'], structure: delivery_challan_stats[4]['structure']},
        {day: delivery_challan_stats[5]['day'], pipe: delivery_challan_stats[5]['pipe'], structure: delivery_challan_stats[5]['structure']},
        {day: delivery_challan_stats[6]['day'], pipe: delivery_challan_stats[6]['pipe'], structure: delivery_challan_stats[6]['structure']},
        {day: delivery_challan_stats[7]['day'], pipe: delivery_challan_stats[7]['pipe'], structure: delivery_challan_stats[7]['structure']}

    ],
    // The name of the data record attribute that contains x-values.
    xkey: 'day',
    xLabelAngle: 70,
    xLabelFormat: function (x) {
            var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
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
