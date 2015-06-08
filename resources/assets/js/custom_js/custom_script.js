/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var baseurl = $('#baseurl').attr('name');
$(function() {
    $("#add_credit").on("click", function() {
//         var numbers = /^[0-9]+$/;
//      if(inputtxt.value.match(numbers))

        if (($("#credit_amt").val() == "") || ($("#credit_amt").val() == false) || !($("#credit_amt").val().match(/^[0-9]+$/))) {
            $(".alert-success3").css("display", "block");
        } else {
            $("#add_credit_form").submit();
        }
    });
    $("#debit_balance").on("click", function() {
        if (($("#debit_amt").val() == "") || ($("#debit_amt").val() == false) || !($("#debit_amt").val().match(/^[0-9]+$/))) {
            $(".alert-success3").css("display", "block");
        } else {
            $("#add_debit_form").submit();
        }
    });
    $("#credit_modal_link_button").on("click", function() {
        $("#credits_modal").modal('show');
    });
});
function show_nav_dropdown() {
    $('#navbar_dropdown').dropdown();
}
/**
 * open_city_delete_modal
 */
function open_city_delete_modal(location_id) {
    $('#myModal3').modal('show');
    var html1 = '<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>' +
            '<button type="button" class="btn btn-default" id="yes" onclick="delete_city(' + "'" + location_id + "'" + ');">Yes</button>';
    $("#delete_city_modal_footer").html(html1);
}

/**
 * Comment
 */


function delete_city(location_id) {
    var csrf_token = $("#delete_city_token").val();
    $.ajax({
        type: "DELETE",
        url: 'city/' + location_id,
        data: {location_id: location_id, _token: csrf_token}
    }).done(function() {
        $("#city_" + location_id).remove();
    });
}

function delete_user(user_id) {
    var csrf_token = $("#delete_user_token").val();
    $.ajax({
        type: "DELETE",
        url: 'users/' + user_id,
        data: {user_id: user_id, _token: csrf_token}
    }).done(function() {
        $("#user_" + user_id).remove();
    });
}

/**
 * Comment
 */
function add_user_comment(user_id, first_name) {
    if (($("#user_comment_" + user_id).val() != "") || ($("#user_comment_" + user_id).val() != false)) {
        var csrf_token = $(".user_comment_modal #user_comment_token").val();
        var user_comment = $("#user_comment_" + user_id).val();
        $.ajax({
            type: "POST",
            url: 'comments',
            data: {user_id: user_id, _token: csrf_token, comment: user_comment}
        }).done(function() {
            var html = '';
            $("#user_comments_container_" + user_id).parent(".slimScrollDiv").show();
            $("#user_comments_container_" + user_id).show();
            html += '<div class="conversation-item item-left clearfix">' +
                    '<div class = "conversation-body">' +
                    '<div class = "name">' + first_name + '</div>' +
                    '<div class = "time hidden-xs">' + " " + moment().format('MMMM Do YYYY, h:mm a') + '</div>' +
                    '<div class = "text">' + user_comment + '</div>' +
                    '</div>' +
                    '</div>';
            $("#user_comments_container_" + user_id).prepend(html);
            $("#user_comment_" + user_id).val("");
            $(".conversation-inner").css("height", "auto !important");
        });
//        $('#user_comment_modal_' + user_id).modal('hide')
    }
    else {
        $(".user_comment_modal #comments_error_container").html("Please add a comment");
//        event.preventDefault();
    }
}

/**
 * Comment
 */
function display_user_comment_modal(user_id) {
//    alert($("#user_comments_container_" + user_id).children().length);

    $("#user_comment_" + user_id).val("");
    $.ajax({
        type: "GET",
        url: 'comments',
        data: {user_id: user_id}
    }).done(function(data) {
//        alert(data);
        var main_array = JSON.parse(data);
        var arr1 = main_array['user_comments'];
        var first_name = '';
        if (arr1.length == 0) {
            $("#user_comments_container_" + user_id).parent(".slimScrollDiv").hide();
            $("#user_comments_container_" + user_id).hide();
        } else {
            var html = '';
            for (var key1 in arr1) {
                for (var key2 in arr1[key1].user_comments) {
                    first_name = arr1[key1].user_comments['first_name'];
                }
                html += '<div class="conversation-item item-left clearfix">' +
                        '<div class = "conversation-body">' +
                        '<div class = "name">' + first_name + '</div>' +
                        '<div class = "time hidden-xs"> ' + moment(arr1[key1]['created_at']).format('Do MMMM YYYY, h:mm a') + '</div>' +
                        '<div class = "text">' + arr1[key1]['comment'] + '</div>' +
                        '</div>' +
                        '</div>';
//                alert(html);

            }
            $("#user_comments_container_" + user_id).html(html);
            $("#user_comments_container_" + user_id).css("height", "auto");
            $(".slimScrollDiv").css("height", "auto");
        }
    });
}

















/**
 * submit_user_edit_form()
 **/
//function submit_user_edit_form(user_id) {
//    alert("ok");
//    var csrf_token = $("#edit_user_token").val();
//    var form_serialized_data = $("#edit_user_form").serialize();
////    alert(form_serialized_data);
//    $.ajax({
//        type: "PUT",
//        url: baseurl + '/admin/users/' + user_id,
////        data: {form_serialized_data: form_serialized_data, _token: csrf_token}
//        data: {_token: csrf_token}
//    });
//}



$(document).ready(function() {
    $("#trade_datepicker").datepicker();


    $('#trade_history_datepicker').daterangepicker({
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

    $('#admin_financials_datepicker').daterangepicker({
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
    $('#admin_financials_datepicker span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

    //Submit admin financials filter//
    $("#filter_admin_financials").on("click", function(event) {
        if (($("#admin_financials_filter").val() == "") || ($("#admin_financials_datepicker").val() == "")) {
            event.preventDefault();
        } else {
            $("#admin_financialsfilter_form").submit();
        }
    });


    $('#commission_transaction_datepicker').daterangepicker({
        startDate: moment().subtract('days', 1),
        endDate: moment(),
        minDate: '01/01/2015',
        maxDate: '12/31/2020',
        dateLimit: {days: 60},
        showDropdowns: true,
        showWeekNumbers: false,
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
        $('#commission_actual_transport_datepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
    );
    $("#user_filter_search_icon").on("click", function(event) {
        if ($("#user_filter").val() == '')
            event.preventDefault();
        else
            $("#user_filter_form").submit();
    });
});

/**
 * Comment
 */
function show_trade_model(user_id, transaction_id, trade_user_name) {

    $('.comment_message').css('display', 'none');
    $('.comment_success').css('display', 'none');

    $('#transaction_id').val(transaction_id);
    $('#trade_user_id').val(user_id);
    $('#trade_user_name').val(trade_user_name);

    $.ajax({
        type: "GET",
        url: 'get_users_comments',
        data: {user_id: user_id}

    }).done(function(data) {

        var main_array = JSON.parse(data);
        var comments = main_array['comment_array'];
        var str = "";
        for (var val in comments) {

            str += '<div class="conversation-item item-left clearfix">' +
                    '     <div class="conversation-body">' +
                    '          <div class="name">' + comments[val].first_name + " " + comments[val].first_name + '</div>' +
                    '          <div class="time hidden-xs"> ' + moment(comments[val].created_at).format('Do MMMM YYYY, h:mm a') +
                    '          </div>' +
                    '     <div class="text">' + comments[val].comment + ' </div>' +
                    '     </div>' +
                    '</div><div class="clearfix"></div>';
        }

        $('.trade_comment_container').html('');
        $('.trade_comment_container').append(str);

    });

    $('#myModal_seller').modal('show');
}


/**
 * add_trade_comments
 */
function add_trade_comment() {

    var trade_user_id = $('#trade_user_id').val();
    var transaction_id = $('#transaction_id').val();
    var comments = $('#trade_comments').val();
    var trade_user_name = $('#trade_user_name').val();

    var user_comment_token = $('#user_comment_token').val();

    if (comments == "") {
        $('.comment_success').css('display', 'none');
        $('.comment_message').css('display', 'block');
        $('#trade_comments').focus();
        return false;
    }

    $.ajax({
        type: "POST",
        url: 'add_trade_comments',
        data: {user_id: trade_user_id, _token: user_comment_token, comments: comments, transaction_id: transaction_id}

    }).done(function() {
        $('.comment_message').css('display', 'none');
        $('.comment_success').css('display', 'block');
        $('#trade_comments').val('');

        var str = '<div class="conversation-item item-left clearfix">' +
                '     <div class="conversation-body">' +
                '          <div class="name">' + trade_user_name + '</div>' +
                '          <div class="time hidden-xs"> ' + moment().format('Do MMMM YYYY, h:mm a') +
                '          </div>' +
                '     <div class="text">' + comments + ' </div>' +
                '     </div>' +
                '</div>';

        $(".trade_comment_container").prepend(str);

    });
}










