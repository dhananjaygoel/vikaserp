$(document).ready(function () {
    $("#checkbox-inl-1").click(function () {
        $(".category_div").toggle("slow");
    });
});

$('.deleteCustomer').click(function () {
    $(this).parents('.modal').find('form').submit();
});

var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

$('#bill_date').datepicker({
    format: 'mm-dd-yyyy',
    startDate: new Date(),
    autoclose: true
});


$('.export_from_date').datepicker({
    format: 'mm-dd-yyyy',
    autoclose: true,
    endDate: new Date()
}).on('changeDate', function(){
    // set the "toDate" start to not be later than "fromDate" ends:
    $('.export_to_date').datepicker('setStartDate', new Date($(this).val()));
}); 

$('.export_to_date').datepicker({
    format: 'mm-dd-yyyy',
    autoclose: true,
    endDate: new Date()
}).on('changeDate', function(){
    // set the "fromDate" end to not be later than "toDate" starts:
    $('.export_from_date').datepicker('setEndDate', new Date($(this).val()));
});




//$('.export_to_date,.export_from_date').datepicker({
//    format: 'mm-dd-yyyy',
//    autoclose: true,
//    endDate: new Date()
//});
$('.export_to_date,.export_from_date').keypress(function (event) {
    event.preventDefault();
});
$('.export_to_date,.export_from_date').bind('cut copy paste', function (e) {
    e.preventDefault();
});
if (($('.export_to_date').val() != "") && ($('.export_from_date').val() != "")) {
    $(".export_btn").removeAttr("disabled");
} else if (($('.export_to_date').val() == "") || ($('.export_from_date').val() == "")) {
    $(".export_btn").attr("disabled", "disabled");
}
$('.export_to_date,.export_from_date').on("change", function () {
    if (($('.export_to_date').val() != "") && ($('.export_from_date').val() != "")) {
        $(".export_btn").removeAttr("disabled");
    } else if (($('.export_to_date').val() == "") || ($('.export_from_date').val() == "")) {
        $(".export_btn").attr("disabled", "disabled");
    }
});
$('#search').keypress(function (e) {
    if (e.keyCode == 13) {
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
    var data_next_button = $(this).attr('data-id');
    if (data_next_button) {
        $("#" + data_next_button).attr('disabled', true).attr('readonly', true);
    }
    var action = $(this).parents('form').attr('action');
    $(this).parents('form').attr('action', action + '?sendsms=true');
    $(this).parents('form').submit();
});
$('.form_button_footer').click(function () {
    var data_next_button = $(this).attr('data-id');
    if (data_next_button) {
        $("#" + data_next_button).attr('disabled', true).attr('readonly', true);
    }
});

$('body').delegate("#sendSMSEditPurchaseOrder", "click", function () {

    var status_form = 0;
    if ($('input[name=supplier_status]:checked').val() == 'new_supplier') {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }
        if ($('#expected_delivery_date').val() == '') {
            $('#expected_delivery_date').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products + 1; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                } else {
                    if ($("#add_purchase_product_name_" + i).val() == "") {
                        $('#add_purchase_product_name_' + i).addClass('error_validation');
                        status_form = 1;
                    }
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
         if ($("#purchase_other_location").val() == "-1") {
            console.log("hii");
            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var action = $(this).parents('form').attr('action');
            $(this).parents('form').attr('action', action + '?sendsms=true');
            $(this).parents('form').submit();
        }

    } else {
        if (($('#existing_supplier_id').val() == "") || ($('#existing_supplier_name').val() == "")) {
            $('#existing_supplier_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products + 1; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                } else {
                    if ($("#add_purchase_product_name_" + i).val() == "") {
                        $('#add_purchase_product_name_' + i).addClass('error_validation');
                        status_form = 1;
                    }
                }

                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
       
        if ($("#purchase_other_location").val() == "-1") {
            console.log("hii");
            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var action = $(this).parents('form').attr('action');
            $(this).parents('form').attr('action', action + '?sendsms=true');
            $(this).parents('form').submit();
        }
    }
});

$('body').delegate("#sendSMSPurchaseOrder", "click", function () {
    var status_form = 0;

    if ($('#datepickerDate').val() == "") {
        $('#datepickerDate').addClass('error_validation');
        status_form = 1;
    }
    if ($('input[name=supplier_status]:checked').val() == 'new_supplier') {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_purchase_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#purchase_other_location").val() == "-1") {
            console.log("hii");
            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var action = $(this).parents('form').attr('action');
            $(this).parents('form').attr('action', action + '?sendsms=true');
            $(this).parents('form').submit();
        }

    } else {
        if ($('#existing_supplier_id').val() == "") {
            $('#existing_supplier_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_purchase_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#purchase_other_location").val() == "-1") {
            console.log("hii");
            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var action = $(this).parents('form').attr('action');
            $(this).parents('form').attr('action', action + '?sendsms=true');
            $(this).parents('form').submit();
        }
    }
});

$('body').delegate(".btn_add_purchase_advice", "click", function () {
    var status_form = 0;

    if ($('#bill_date').val() == "") {
        $('#bill_date').addClass('error_validation');
        status_form = 1;
    }
    if ($('#datepickerDate1').val() == "") {
        $('#datepickerDate1').addClass('error_validation');
        status_form = 1;
    }
    if ($('input[name=supplier_status]:checked').val() == 'new') {

        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#credit_period').val() == '') {
            $('#credit_period').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var action = $(this).parents('form').attr('action');
            $(this).parents('form').attr('action', action + '?sendsms=true');
            $(this).parents('form').submit();
        }
    } else {
        if ($('#customer_default_location').val() == "") {
            $('#customer_default_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#supplier_select').val() == "0") {
            $('#supplier_select').addClass('error_validation');
            status_form = 1;
        }
        if ($('#cp').val() == "") {
            $('#cp').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var action = $(this).parents('form').attr('action');
            $(this).parents('form').attr('action', action + '?sendsms=true');
            $(this).parents('form').submit();
        }
    }
});

$('body').delegate(".btn_edit_purchase_advice", "click", function () {

    var status_form = 0;
   
    if ($('input:text[name=vehicle_number]').val() == "") {
        $('input:text[name=vehicle_number]').addClass('error_validation');
        status_form = 1;
    }
    var tot_products = $(".add_product_row").length;
    var j = 0;
    var present_shipping_zero_count = 0;
    var actual_pieces_count = 0;
    for (var i = 1; i <= tot_products + 1; i++) {
//        if ($("#present_shipping" + i).val() == 0 | $("#present_shipping" + i).val() == "") {
//            present_shipping_zero_count++;
//        }
        if (($("#add_product_id_" + i).val() == "") && ($("#product_price_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "") {
                $('#add_product_name_' + i).addClass('error_validation');
                status_form = 1;
            } else {
                if ($('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
            if ($("#product_price_" + i).val() == "") {
                $('#product_price_' + i).addClass('error_validation');
                status_form = 1;
            }
        }
    }
    
    for (var i = 0; i <= tot_products - 1; i++) {
        
        if ($("#actual_pieces" + i).val() == '0'  | $("#actual_pieces" + i).val() =="") {
            actual_pieces_count++;
        }
    }
    if ((tot_products-1) == actual_pieces_count) {
        console.log("hi");
        for (var j = 0; j <= tot_products-1; j++) {
            $('#actual_pieces' + j).addClass('error_validation');
        }
        status_form = 1;
    }else{
        for (var j = 1; j <= tot_products-1; j++) {
            $('#actual_pieces' + j).removeClass('error_validation');
        }
    }
   
   for (var i = 0; i <= tot_products - 1; i++) {
        if ($("#present_shipping" + i).val() == 0  | $("#present_shipping" + i).val() =="") {
            present_shipping_zero_count++;
        }
    }
    if ((tot_products-1) == present_shipping_zero_count) {
        
        for (var j = 0; j <= tot_products-1; j++) {
            $('#present_shipping' + j).addClass('error_validation');
        }
        status_form = 1;
    }else{
        for (var j = 1; j <= tot_products-1; j++) {
            $('#present_shipping' + j).removeClass('error_validation');
        }
    }
    
//    if ((tot_products-1) == present_shipping_zero_count) {
//        for (var j = 0; j <= tot_products-1 ; j++) {
//            $('#present_shipping' + j).addClass('error_validation');
//        }
//        status_form = 1;
//    }
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $(this).parents('form').submit();
    }
});

/*
 * Print challan on the page delivery order
 * @param {type} delivery_order_id
 * @returns {undefined}
 */
function print_challan(delivery_order_id) {
    $('#print_delivery_order').val(delivery_order_id);
}
/*
 * print challan to new page on delivery order  
 */
$('.print_delivery_order').click(function () {
    $('.print_delivery_order').text('Please wait..').prop('disabled', 'disabled');
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked

    $.ajax({
        type: "GET",
        url: base_url + '/print_delivery_order/' + $(this).val() + '?send_sms=' + send_sms,
        success: function (data) {
            $('#print_challan').modal('hide');
            var printWindow = window.open('about:blank');
            printWindow.document.open();
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                $('.print_delivery_order').removeprop('disabled');
                location.reload();
            };
        }
    });
});
function print_delivery_challan(challan_id) {
    $('#print_delivery_challan').val(challan_id);
}
$('.print_delivery_challan').click(function () {
    $('.print_delivery_challan').html('Please wait..').prop('disabled', 'disabled');
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_delivery_challan/' + $('#print_delivery_challan').val() + '?send_sms=' + send_sms,
        success: function (data) {
            $('#print_challan').modal('hide');
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                $('.print_delivery_challan').html('Generate Challan').prop("disabled", false);
                location.reload();
            };
        }
    });
});

$('.print_inventory_report_list').click(function () {
    $('.print_inventory_report').html('Please wait..').prop('disabled', 'disabled');
    var product_id = $(this).data('id');
    var base_url = $('#baseurl').attr('name');    
    $.ajax({
        type: "GET",
        url: base_url + '/print_inventory_report/' + product_id,
        success: function (data) {
            $('#print_inventory_modal').modal('hide');
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {                
                location.reload();
            };
        }
    });
});

$('.print_inventory_price_list').click(function () {
    $('.print_inventory_price_list').html('Please wait..').prop('disabled', 'disabled');
    var product_id = $(this).data('id');
    var base_url = $('#baseurl').attr('name');    
    $.ajax({
        type: "GET",
        url: base_url + '/print_inventory_price_list/' + product_id,
        success: function (data) {
            $('#print_inventory_price_list').modal('hide');
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {                
                location.reload();
            };
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
            printWindow.onunload = function () {
                location.reload();
            };
        }
    });
});
function print_purchase_challan(purchase_challan_id) {
    $('#purchase_challan_id').val(purchase_challan_id);
}
$('.print_purchase_challan').click(function () {
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_challan/' + $('#purchase_challan_id').val() + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                location.reload();
            };
        }
    });
});
/*
 * set purchase advice id for print form
 * @param {type} purchase_advice_id
 * @returns {integer}
 */
function print_purchase_advice(purchase_advice_id) {
    $('#pa_id').val(purchase_advice_id);
}
/*
 * print purchase advice
 */
$('.print_purchase_advise').click(function () {
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_advise/' + $('#pa_id').val() + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                location.reload();
            };
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
            printWindow.onunload = function () {
                location.reload();
            };
        }
    });
});
/*
 | RESTRICT REDIRECT ON HALF FILLED FORM STARTS
 */
//var GLOBAL_NAMESPACE = {};
//GLOBAL_NAMESPACE.value_changed = false;
//$(document).ready(function () {
//    setup();
//    $('a').click(function (e) {
//        if (GLOBAL_NAMESPACE.value_changed) {
//            return confirm("are you sure?");
//        }
//    });
//});
//
//function setup() {
//    // bind the change event to all editable fields
//    $("form :input").bind("change", function (e) {
//        GLOBAL_NAMESPACE.value_changed = true;
//    });
//}
//$(document).ajaxStart(function () {
//    GLOBAL_NAMESPACE.value_changed = false;
//});


$(document).ready(function () {
    $('#onenter_prevent').confirmExit('Go away?');
});

/*
 | RESTRICT REDIRECT ON HALF FILLED FORM ENDS
 */
//function display_error_message() {
//    $("#export_error_message").removeClass("hidden");
//}