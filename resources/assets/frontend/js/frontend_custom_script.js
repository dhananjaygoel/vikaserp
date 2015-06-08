var baseurl = $('#baseurl').attr('name');
$(function() {
    $("#actual_transport_datepicker").datepicker({
        format: 'dd-mm-yyyy'
    });

    $('#booking_history_datepicker').daterangepicker({
        startDate: moment().subtract('days', 29),
        endDate: moment(),
        minDate: '01/01/2015',
        maxDate: '12/31/2020',
        dateLimit: {days: 60},
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
//            'Today': [moment(), moment()],
//            'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
//            'Last 7 Days': [moment().subtract('days', 6), moment()],
//            'Last 30 Days': [moment().subtract('days', 29), moment()],
//            'This Month': [moment().startOf('month'), moment().endOf('month')],
//            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
            applyLabel: 'Submit',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    },
    function(start, end) {
        console.log("Callback has been called!");
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    );
    //Set the initial state of the picker label
    $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

    //datepicker
    $('#actual_transport_datepicker').datepicker({
        format: 'mm-dd-yyyy'
    });

    get_notifications();

});

/**
 * Comment
 */
function get_notifications() {
    $.ajax({
        type: "GET",
        url: 'notifications'
    }).done(function(data) {
        var main_array = JSON.parse(data);
        var arr1 = main_array['notifications'];
        var html = '';
        var i = 0;
        for (var key in arr1) {
//            var now = moment();
//            var m = moment(arr1[key].created_at);
//            var d = moment.duration(now - m);
//            if (d.hours() == 0) {
//                var s = d.minutes() + " seconds";
//            }
//            else if (d.hours() > 0) {
//                var s = d.hours() + d.minutes() + " minutes";
//            }
//            var x = moment(arr1[key].created_at, "Y-m-d H:i:s").fromNow();
            if (arr1[key].status == "unread") {
                html += '<li class="item" onclick="change_notification_status(' + arr1[key].id + ');"><i class="fa fa-envelope"></i><span class="content">Your truck booking for ' + arr1[key]['transaction']['scripts'].script_name + ' has been confirmed</span>' +
                        '<span class="time pull-right"><i class="fa fa-clock-o"></i> ' + moment(arr1[key].created_at).format("Do MMM YYYY H:mm:s") + '</span></li>';
                i++;
            }
        }
        if (i != 0) {
            $("#current_notifications").html(html);
            $("#notifications_count2").html("You have " + i + " new notifications");
            $("#notification_count").text(i);
        }
        else if (i == 0) {
            $("#notification_count").hide();
        }
    });
}

/**
 * Comment
 */
function change_notification_status(notification_id) {
    var _token = $("#csrf_token").attr("content");
    $.ajax({
        type: "POST",
        url: 'change_notification_status?notification_id=' + notification_id,
        data: {notification_id: notification_id, _token: _token}
    }).done(function() {
        window.location.href = 'booking_history';
    });
}

/**
 * open_modify_limit_modal(
 */
function open_modify_limit_modal(bid_td_id, inter_spread, ask_td_id) {
    if ($("#optionsRadios3").is(':checked')) {
        if ($("#" + ask_td_id).text() == '')
            $("#booking_limit_price").val("");
        else
            $("#booking_limit_price").val(parseInt($("#" + ask_td_id).text()) + parseInt(inter_spread));
    } else
    if ($("#optionsRadios4").is(':checked')) {
        if ($("#" + bid_td_id).text() == '')
            $("#booking_limit_price").val("");
        else
            $("#booking_limit_price").val(parseInt($("#" + bid_td_id).text()) + parseInt(inter_spread));
    }

}


$(document).ready(function() {
    $("#actual_transport_datepicker").datepicker();



//$('#trade_datepicker').datepicker({
//                    format: 'mm-dd-yyyy'
//                });
    $('#balance_date_range').daterangepicker({
        startDate: moment().subtract('days', 29),
        endDate: moment(),
        minDate: '01/01/2015',
        maxDate: '12/31/2020',
        dateLimit: {days: 60},
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
//                        'Today': [moment(), moment()],
//                        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
//                        'Last 7 Days': [moment().subtract('days', 6), moment()],
//                        'Last 30 Days': [moment().subtract('days', 29), moment()],
//                        'This Month': [moment().startOf('month'), moment().endOf('month')],
//                        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
            applyLabel: 'Submit',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    },
    function(start, end) {
        console.log("Callback has been called!");
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    );
    //Set the initial state of the picker label
    $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

    //datepicker
    $('#actual_transport_datepicker').datepicker({
        format: 'mm-dd-yyyy'
    });
    $("#filter_booking_history").on("click", function(e) {
        if (($('#source_filter').val() == "") || ($('#destination_filter').val() == "") || ($('#booking_history_datepicker').val() == "") || ($('#actual_transport_datepicker').val() == "")) {
            alert("Please enter filter values");
            e.preventDefault();
        }
        else {
            $("#filter_booking_history_form").submit();
        }
    });
});
/**
 * Comment
 */
function make_script_favourite(script_id) {
    $("#favourite_script_form_" + script_id).submit();
}

/**
 * Comment
 */
function get_all_favourite_scripts(favourite_scripts) {
    $.ajax({
        type: "GET",
        url: 'bookings',
        data: {favourite_scripts: favourite_scripts}
    }).done(function() {
        alert("ok");
    });
}

/**
 * Comment
 */
function form_submit() {
    if ($("#get_favourite_all").val() == 0)
        $("#get_favourite_all").val(1);
    else if ($("#get_favourite_all").val() == 1)
        $("#get_favourite_all").val(0);
    $("#all_favourite_scripts_form").submit();
}