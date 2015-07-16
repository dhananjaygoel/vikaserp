$(document).ready(function () {
    $("#checkbox-inl-1").click(function () {

        $(".category_div").toggle("slow");

    });
});

$('.deleteCustomer').click(function () {
    $(this).parents('.modal').find('form').submit();
});

$('#bill_date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});

$('#search').keypress(function (e) {
    if (e.keyCode == 13)
    {
        $('#searchCustomerForm').submit();
    }
});

$("#optionsRadios1").click(function () {
    $(".exist_field").hide();
    $(".customer_select").show();
});
$("#optionsRadios3").click(function () {
    $(".exist_field").show();
    $(".customer_select").hide();
});
$("#optionsRadios1").click(function () {
    $(".supplier").show();

});
$("#optionsRadios3").click(function () {
    $(".supplier").hide();

});
$("#optionsRadios6").click(function () {
    $(".plusvat").show();

});
$("#optionsRadios5").click(function () {
    $(".plusvat").hide();

});

$(document).ready(function () {
    $("#addmore1").click(function () {
        $(".row5").hide();
        $(".row6").show();
        $(".row7").show();
    });
    $("#addmore2").click(function () {
        $(".row7").hide();
        $(".row8").show();
        $(".row9").show();
    });
    $("#addmore3").click(function () {
        $(".row9").hide();
        $(".row10").show();
        $(".row11").show();
    });
    $("#addmore4").click(function () {
        $(".row11").hide();
        $(".row12").show();

    });

    $('#loc1').change(function () {
        if ($('#loc1').val() == '3') {
            $('.locationtext').toggle();

        }
    });

    $('#loc1').change(function () {
        if ($('#loc1').val() == 'other') {
            $('.locationtext').toggle();

        }


    });


});

$('#purchaseaAdviseFilter').on('change', function () {
    $('#purchaseaAdviseFilterForm').submit();
});

$('#sendSMS').click(function () {
    var action = $(this).parents('form').attr('action');
    $(this).parents('form').attr('action', action + '?sendsms=true');
    $(this).parents('form').submit();
})

$('.print_delivery_order').click(function () {
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_delivery_order/' + $(this).attr('id') + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            window.setTimeout(function(){location.reload()},5000)
        }
    });
});
$('.print_delivery_challan').click(function () {
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_delivery_challan/' + $(this).attr('id') + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            window.setTimeout(function(){location.reload()},5000)
        }
    });
});
$('.print_sales_order_daybook').click(function () {
    var base_url = $('#baseurl').attr('name');
    $.ajax({
        type: "GET",
        url: base_url + '/print_sales_order_daybook',
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            window.setTimeout(function(){location.reload()},5000)
        }
    });
});

$('.print_purchase_challan').click(function () {
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_challan/' + $(this).attr('id') + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            window.setTimeout(function(){location.reload()},5000)
        }
    });
});

$('.print_purchase_advise').click(function () {
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_advise/' + $(this).attr('id') + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            window.setTimeout(function(){location.reload()},5000)
        }
    });
});

$('.print_purchase_daybook').click(function () {
    var base_url = $('#baseurl').attr('name');
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_daybook',
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            window.setTimeout(function(){location.reload()},5000)
        }
    });
});
/*
 | RESTRICT REDIRECT ON HALF FILLED FORM STARTS
 */
var GLOBAL_NAMESPACE = {};
GLOBAL_NAMESPACE.value_changed = false;
$(document).ready(function () {
    setup();
    $('a').click(function (e) {
        if (GLOBAL_NAMESPACE.value_changed) {
            return confirm("are you sure?");
        }
    });
});

function setup() {
    // bind the change event to all editable fields
    $("input,select").bind("change", function (e) {
        GLOBAL_NAMESPACE.value_changed = true;
    });
}
$(document).ajaxStart(function() {  
    GLOBAL_NAMESPACE.value_changed = false;
});
/*
 | RESTRICT REDIRECT ON HALF FILLED FORM ENDS
 */