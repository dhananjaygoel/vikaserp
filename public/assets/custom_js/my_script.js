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
}).on('changeDate', function () {
    // set the "toDate" start to not be later than "fromDate" ends:
    $('.export_to_date').datepicker('setStartDate', ($(this).val()));
});

$('.export_to_date').datepicker({
    format: 'mm-dd-yyyy',
    autoclose: true,
    endDate: new Date()
}).on('changeDate', function () {
    // set the "fromDate" end to not be later than "toDate" starts:
    $('.export_from_date').datepicker('setEndDate', ($(this).val()));
});




//$('.export_to_date,.export_from_date').datepicker({
//    format: 'mm-dd-yyyy',
//    autoclose: true,
//    endDate: new Date()
//});
$('.export_to_date,.export_from_date').keypress(function (event) {
    if (event.keyCode != 8) {
        event.preventDefault();
    }
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

$("#existing_customer,#new_customer").click(function () {    
    $("#flash_error").hide();
    $(".alert-warning").hide();
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

$('body').delegate("#sendSMSEditPurchaseOrder , .btn_edit_purchase_order", "click", function () {

    var status_form = 0;
     if ($('input[name=vat_status]:checked').val() == "exclude_vat") {
        if ($('#price').val() == "" | $('#price').val() == "0" | $('#price').val() == 0 ) {
            $('#price').addClass('error_validation');
            status_form = 1;
        }
    }else{
         $('#price').removeClass('error_validation');
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
        if ($('#expected_delivery_date').val() == '') {
            $('#expected_delivery_date').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#chk_vat_" + i).attr('disabled',true))) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_purchase_product_name_' + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                } 
                // else {
                //     if ($("#add_purchase_product_name_" + i).val() == "") {
                //         $('#add_purchase_product_name_' + i).addClass('error_validation');
                //         status_form = 1;
                //     }
                // }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#units_' + i).val() == "") {
                    $('#units_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#length_' + i).val() == "" && $('#length_' + i).is(':enabled')) {
                    $('#length_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if ($("#purchase_other_location").val() == "-1") {
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
            var curid = $(this).attr("id");
            if (curid == "sendSMSEditPurchaseOrder") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
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
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#chk_vat_" + i).attr('disabled',true))) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_purchase_product_name_' + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                } 
                // else {
                //     if ($("#add_purchase_product_name_" + i).val() == "") {
                //         $('#add_purchase_product_name_' + i).addClass('error_validation');
                //         status_form = 1;
                //     }
                // }

                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#units_' + i).val() == "") {
                    $('#units_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#length_' + i).val() == "" && $('#length_' + i).is(':enabled')) {
                    $('#length_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }

        if ($("#purchase_other_location").val() == "-1") {
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
            var curid = $(this).attr("id");
            if (curid == "sendSMSEditPurchaseOrder") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }
});

$('body').delegate("#sendSMSPurchaseOrder, .btn_add_purchase_order", "click", function () {
    var status_form = 0;
//    console.log($('input[name=vat_status]:checked').val());

    if ($('#datepickerDate').val() == "") {
        $('#datepickerDate').addClass('error_validation');
        status_form = 1;
    }
    if ($('input[name=vat_status]:checked').val() == "exclude_vat") {
        if ($('#price').val() == "" | $('#price').val() == "0" | $('#price').val() == 0 ) {
            $('#price').addClass('error_validation');
            status_form = 1;
        }
    }else{
         $('#price').removeClass('error_validation');
    }
    if ($('.unit').val() == "") {
        $('.unit').addClass('error_validation');
        status_form = 1;
    }else{$('.unit').removeClass('error_validation');}

    if ($('input[name=supplier_status]:checked').val() == 'new_supplier') {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('.unit').val() == "") {
            $('.unit').addClass('error_validation');
            status_form = 1;
        }else{$('.unit').removeClass('error_validation');}
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
        for (var i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#chk_vat_" + i).attr('disabled',true))) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    $('#product_all_' + i).addClass('error_validation');
                    status_form = 1;
                } else {
                    $('#product_all_' + i).removeClass('error_validation');
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#units_' + i).val() == "") {
                    $('#units_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#length_' + i).val() == "" && $('#length_' + i).is(':enabled')) {
                    $('#length_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_purchase_product_name_1').addClass('error_validation');
                $('#product_all_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#purchase_other_location").val() == "-1") {
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
            var curid = $(this).attr("id");
            if (curid == "sendSMSPurchaseOrder") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_supplier_id').val() == "" || $('#existing_supplier_name').val() == "") {
            $('#existing_supplier_name').addClass('error_validation');
            $('.uploader').addClass('error_validation');
            status_form = 1;
        } else {
            $('.uploader').removeClass('error_validation');
        }

        if ($('#purchase_other_location').val() == "0") {
            $('#purchase_other_location').addClass('error_validation');
            status_form = 1;
        } else {
            $('#purchase_other_location').removeClass('error_validation');
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#chk_vat_" + i).attr('disabled',true))) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    $('#product_all_' + i).addClass('error_validation');
                    status_form = 1;
                } else {
                    $('#product_all_' + i).removeClass('error_validation');
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#units_' + i).val() == "") {
                    $('#units_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($('#length_' + i).val() == "" && $('#length_' + i).is(':enabled')) {
                    $('#length_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_purchase_product_name_1').addClass('error_validation');
                $('#product_all_1').addClass('error_validation');
            } else {
                $('#product_all_1').removeClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#purchase_other_location").val() == "-1") {
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
        // if ($('.unit').val() == "") {
        //     $('.unit').addClass('error_validation');
        //     status_form = 1;
        // }else{$('.unit').removeClass('error_validation');}
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "sendSMSPurchaseOrder") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
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
            if (($("#add_product_id_" + i).val() == "") && ($("#chk_vat_" + i).attr('disabled',true))) {
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
            if (($("#add_product_id_" + i).val() == "") && ($("#chk_vat_" + i).attr('disabled',true))) {
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
    for (var i = 0; i <= tot_products; i++) {
//        if ($("#present_shipping" + i).val() == 0 | $("#present_shipping" + i).val() == "") {
//            present_shipping_zero_count++;
//        }
        if (($("#add_product_id_" + i).val() == "") && ($("#product_price_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "" || $('#add_purchase_product_name_' + i).val() == "") {
                $('#add_purchase_product_name_' + i).addClass('error_validation');
                status_form = 1;
            } else {
                if ($('#add_purchase_product_name_' + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
            if ($("#product_price_" + i).val() == "") {
                $('#product_price_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#quantity_" + i).val() == "") {
                $('#quantity_' + i).addClass('error_validation');
                status_form = 1;
            }else{$('#quantity_' + i).removeClass('error_validation');}
            if ($('#units_' + i).val() == "") {
                $('#units_' + i).addClass('error_validation');
                status_form = 1;
            }else{$('#units_' + i).removeClass('error_validation');}
            if ($('#length_' + i).val() == "" && $('#length_' + i).is(':enabled')) {
                $('#length_' + i).addClass('error_validation');
                status_form = 1;
            }else{$('#length_' + i).removeClass('error_validation');}
            if (($('#actual_pieces' + i).val() == "") || $("#actual_pieces" + i).val() == 0) {
                $('#actual_pieces' + i).addClass('error_validation');
                status_form = 1;
            }else{$('#actual_pieces' + i).removeClass('error_validation');}
            if ($("#present_shipping" + i).val() == 0 | $("#present_shipping" + i).val() == "") {
                $('#present_shipping' + i).addClass('error_validation');
                status_form = 1;
            }else{$('#present_shipping' + i).removeClass('error_validation');}
        }
    }

    for (var i = 0; i <= tot_products - 1; i++) {

        if ($("#actual_pieces" + i).val() == 0 || $("#actual_pieces" + i).val() == "") {
            actual_pieces_count++;
        }
    }
    if ((tot_products - 1) == actual_pieces_count) {
        // console.log("hi");
        for (var j = 0; j <= tot_products - 1; j++) {
            $('#actual_pieces' + j).addClass('error_validation');
        }
        status_form = 1;
    } 
    // else {
    //     for (var j = 1; j <= tot_products - 1; j++) {
    //         $('#actual_pieces' + j).removeClass('error_validation');
    //     }
    // }

    for (var i = 0; i <= tot_products - 1; i++) {
        if ($("#present_shipping" + i).val() == 0 | $("#present_shipping" + i).val() == "") {
            present_shipping_zero_count++;
        }
    }
    if ((tot_products - 1) == present_shipping_zero_count) {

        for (var j = 0; j <= tot_products - 1; j++) {
            $('#present_shipping' + j).addClass('error_validation');
        }
        status_form = 1;
    } 
    // else {
    //     for (var j = 1; j <= tot_products - 1; j++) {
    //         $('#present_shipping' + j).removeClass('error_validation');
    //     }
    // }

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

function print_challan_do(el) {    
    $('#print_delivery_order').val(el.id);
    var is_gst = $('#is_gst'+el.id).val();
    var empty_truck_weight = $(el).data('bind');
    var customer_type = $(el).data('customer_type');
    var vehicle_number = $(el).data('vehicle_number');
//    $('#print_delivery_order').data("customer_type",customer_type);
    $('#print_delivery_order').attr("data-customer_type",customer_type);    
    $('#empty_truck_weight').val(empty_truck_weight);
    $('#vehicle_no').val(vehicle_number);
    $('#vehicle_no').attr('value',vehicle_number);
    if($('#vehicle_no').hasClass('error_validation')){        
        $('#vehicle_no').removeClass('error_validation');
    }
    if($('#empty_truck_weight').hasClass('error_validation')){        
        $('#empty_truck_weight').removeClass('error_validation');
    }
    if(customer_type=='supplier'){
        $('#empty_truck_weight').css('display',"none");
        $('.empty_truck_weight_title').css('display',"none");
//        $('#vehicle_no').css('display',"none");
    }else{
        $('#empty_truck_weight').val(empty_truck_weight);
        $('#empty_truck_weight').css('display',"block");
        $('.empty_truck_weight_title').css('display',"block");
//        $('#vehicle_no').css('display',"block");
    }
    if(is_gst == 1){
        $("#checksms").prop("checked", true);
        $("#checkwhatsapp").prop("checked", true);
    }else{
        $("#checksms").prop("checked", false);
        $("#checkwhatsapp").prop("checked", false);
    }
}
/*
 * print challan to new page on delivery order  
 */
$('.print_delivery_order').click(function () {
    var empty_truck_weight = parseInt($('#empty_truck_weight').val());
    var vehicle_number = $('#vehicle_no').val()
    var customer_type = $(this).data('customer_type');        
//    console.log(customer_type);
    var flag = true;
    if(customer_type=='supplier'){
        $('#empty_truck_weight').attr('value',0)
//        $('#vehicle_no').attr('value',0)
        if(vehicle_number ==""){            
            $('#vehicle_no').addClass('error_validation');
            return false;            
        }
    }else{
        if(empty_truck_weight == "0" | empty_truck_weight =="" | isNaN(empty_truck_weight)){
            $('#empty_truck_weight').addClass('error_validation');
            flag = false;
        }else{
            $('#empty_truck_weight').removeClass('error_validation');
        }
        if(vehicle_number ==""){            
            $('#vehicle_no').addClass('error_validation');
            flag = false;            
        }else{
            $('#vehicle_no').removeClass('error_validation');
        }
        if(flag==false){
            return false;
        }
    }
           
    $('.print_delivery_order').text('Please wait..').prop('disabled', 'disabled');
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    var send_whatsapp = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked

    if ($("#checkwhatsapp").is(':checked'))
        send_whatsapp = true;  // checked
    else
        send_whatsapp = false;  // unchecked

    $.ajax({
        type: "GET",
        data: {send_whatsapp:send_whatsapp,empty_truck_weight:empty_truck_weight,vehicle_number:vehicle_number,customer_type:customer_type},
        url: base_url + '/print_delivery_order/' + $(this).val() + '?send_sms=' + send_sms,
        success: function (data) {
            console.log(data);
            $('#print_challan').modal('hide');
            if(data == "failed"){
                $('#flash_error').html('You can not print many time, please contact your administrator');
                location.reload();
            }else{
                var printWindow = window.open(data);
                // var printWindow = window.open('', '');
    //            printWindow.document.open();
                if (printWindow) {
                    printWindow.document.write(data);
                    printWindow.print();
                    printWindow.close();
                    printWindow.onunload = function () {
    //                location.reload();

                    };
                    $('.print_delivery_order').html('Generate DO').prop("disabled", false);
                    location.reload();
                }
            }
        }
    });
});
function print_delivery_challan(challan_id,allinc) {
    $('#print_delivery_challan').val(challan_id);
    var is_gst = $('#is_gst'+challan_id).val();
    if(allinc){
        $("#checksms").prop("disabled", false);
        $("#checkwhatsapp").prop("disabled", false);
        $('#checksms_span').attr('data-original-title','SMS would be sent to Party');
    }
    else{
        $("#checksms").prop("disabled", true);
        $("#checkwhatsapp").prop("disabled", true);
        $("#checksms").prop("checked", false);
        $("#checkwhatsapp").prop("checked", false);
        $('#checksms_span').attr('data-original-title','Sending SMS not allowed');
    }
    if(is_gst == 1){
        $("#checksms").prop("checked", true);
        $("#checkwhatsapp").prop("checked", true);
    }else{
        $("#checksms").prop("checked", false);
        $("#checkwhatsapp").prop("checked", false);
    }

}


$('.print_delivery_challan').click(function () {
    $('.print_delivery_challan').html('Please wait..').prop('disabled', 'disabled');
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    var send_whatsapp = '';
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked

    if ($("#checkwhatsapp").is(':checked'))
        send_whatsapp = true;  // checked
    else
        send_whatsapp = false;  // unchecked

    $.ajax({
        type: "GET",
        data: {send_whatsapp:send_whatsapp},
        url: base_url + '/print_delivery_challan/' + $('#print_delivery_challan').val() + '?send_sms=' + send_sms,
        success: function (data) {
            $('#print_challan').modal('hide');
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
            };
            $('.print_delivery_challan').html('Generate Challan').prop("disabled", false);
            location.reload();
        }
    });
});

$('.print_inventory_report_list').click(function (event) {
    // event.preventDefault();
//    $('.print_inventory_report_list').html('Please wait..').prop('disabled', 'disabled');
    var product_id = $(this).data('id');
    var dropdown_value = $('#inventory_report_dropdown').val();
    var base_url = $('#baseurl').attr('name');
    var tbl=$("#day-wise").html();
    var newdata='<html>    <head>        <title>Delivery Order</title>        <meta charset="windows-1252">        <meta name="viewport" content="width=device-width, initial-scale=1.0">        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>    </head>    <body><style>.crossout{    /width: 120px;/   min-width: 150px;   min-width: 150px;   width: 150px;   background-image: linear-gradient(to bottom left,  transparent calc(50% - 1px), #DDDDDD, transparent calc(50% + 1px));}.thickness-head{   float: right;   margin-top: -10px;}.size-head{    float: left;    margin-top: 20px;}.table {    margin-bottom: 6px !important;}.table-bordered {    border: 1px solid #ddd;}.table {    margin-bottom: 20px;    max-width: 100%;    width: 100%;}.text-center {    text-align: center;}.table-bordered {    border: 1px solid #ddd;}.table {    margin-bottom: 20px;    max-width: 100%;    width: 100%;}.text-center {    text-align: center;}table {    background-color: transparent;}table {    border-collapse: collapse;    border-spacing: 0;}.table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {    border-top: 1px solid #ddd;    line-height: 1.42857;    padding: 8px;    vertical-align: top;}</style><table id="day-wise" class="table table-bordered text-center complex-data-table">'+ tbl +'</table></body></html>';
    newdata = newdata.replace(/\s\s+/g, ' ');
    var printWindow = window.open(newdata);
    // var printWindow = window.open('', '');
    printWindow.document.write(newdata);
    printWindow.print();
    printWindow.close();
    printWindow.onunload = function () {
        $('#print_inventory_modal').modal('hide');
            //    location.reload();
    };
//     $.ajax({
//         type: "GET",
//         data: {dropdown_value:dropdown_value},
//         url: base_url + '/print_inventory_report/' + product_id,
//         success: function (data) {
//             $('#print_inventory_modal').modal('hide');
//             var tbl=$("#day-wise").html();
//             var newdata='<html>    <head>        <title>Delivery Order</title>        <meta charset="windows-1252">        <meta name="viewport" content="width=device-width, initial-scale=1.0">        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>    </head>    <body><style>.crossout{    /width: 120px;/   min-width: 150px;   min-width: 150px;   width: 150px;   background-image: linear-gradient(to bottom left,  transparent calc(50% - 1px), #DDDDDD, transparent calc(50% + 1px));}.thickness-head{   float: right;   margin-top: -10px;}.size-head{    float: left;    margin-top: 20px;}.table {    margin-bottom: 6px !important;}.table-bordered {    border: 1px solid #ddd;}.table {    margin-bottom: 20px;    max-width: 100%;    width: 100%;}.text-center {    text-align: center;}.table-bordered {    border: 1px solid #ddd;}.table {    margin-bottom: 20px;    max-width: 100%;    width: 100%;}.text-center {    text-align: center;}table {    background-color: transparent;}table {    border-collapse: collapse;    border-spacing: 0;}.table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {    border-top: 1px solid #ddd;    line-height: 1.42857;    padding: 8px;    vertical-align: top;}</style><table id="day-wise" class="table table-bordered text-center complex-data-table">'+ tbl +'</table></body></html>';
//             var printWindow = window.open(newdata);
//             // var printWindow = window.open('', '');
//             printWindow.document.write(newdata);
//             printWindow.print();
//             printWindow.close();
//             printWindow.onunload = function () {
// //                location.reload();
//             };
// //             window.location.reload();
//         }
//     });
});

$('.print_account_customers').click(function () {
    var base_url = $('#baseurl').attr('name');
    var search = $('#due-payment-form').find('#search_filter').val();
    var territory_filter = $('#due-payment-form').find('#territory_filter').val();
    var date_filter = $('#due-payment-form').find('#date_filter').val();
    var location_filter = $('#due-payment-form').find('#location_filter').val();
    $.ajax({
        type: "GET",
        url: base_url + '/print_account_customers',
        data: {
            search: search, territory_filter: territory_filter, date_filter: date_filter, location_filter: location_filter,
        },
        success: function (data) {
            $('#print_account_customers').modal('hide');
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
//                location.reload();
            };
//             location.reload();
        }
    });
});

$('.print_customers_details').click(function () {
    var base_url = $('#baseurl').attr('name');
    var date_filter = $('#customer-details-form').find('#date_filter').val();
    var settle_filter = $('#customer-details-form').find('#settle_filter').val();
    var customer_id = $(this).data('id');
    $.ajax({
        type: "GET",
        url: base_url + '/print_customers_details',
        data: {
            date_filter: date_filter, settle_filter: settle_filter, customer_id: customer_id
        },
        success: function (data) {
            $('#print_account_customers').modal('hide');
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                location.reload();
            };
            location.reload();
        }
    });
});

$('.print_inventory_price_list').click(function () {
//    $('.print_inventory_price_list').html('Please wait..').prop('disabled', 'disabled');
    var product_id = $(this).data('id');
    var base_url = $('#baseurl').attr('name');
    $.ajax({
        type: "GET",
        url: base_url + '/print_inventory_price_list/' + product_id,
        success: function (data) {
            $('#print_inventory_price_list').modal('hide');
            data = data.replace(/\s\s+/g, ' ');
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
//                location.reload();
            };
//             location.reload();
        }
    });
});

$('.print_sales_order_daybook').click(function () {
    var base_url = $('#baseurl').attr('name');
    var export_from_date = $('#export_from_date').val();
    var export_to_date = $('#export_to_date').val();
    var _token = $('#_token').val();
    $.ajax({
        type: "POST",
        data: {export_from_date: export_from_date, export_to_date: export_to_date, _token: _token},
        url: base_url + '/print_sales_order_daybook',
        success: function (data) {
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            if (printWindow) {
                printWindow.document.write(data);
                printWindow.print();
                printWindow.close();
                printWindow.onunload = function () {
                    location.reload();
                };
                location.reload();
            }
        }
    });
});
$('.print_daily_proforma').click(function () {
    var base_url = $('#baseurl').attr('name');
    var export_from_date = $('#export_from_date').val();
    var export_to_date = $('#export_to_date').val();
    var _token = $('#_token').val();
    $.ajax({
        type: "POST",
        data: {export_from_date: export_from_date, export_to_date: export_to_date, _token: _token},
        url: base_url + '/print_daily_proforma',
        success: function (data) {
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            if (printWindow) {
                printWindow.document.write(data);
                printWindow.print();
                printWindow.close();
                printWindow.onunload = function () {
                    location.reload();
                };
                location.reload();
            }
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
    if ($("#checkwhatsapp").is(':checked'))
        send_whatsapp = true;  // checked
    else
        send_whatsapp = false;  // unchecked
    $.ajax({
        type: "GET",
        data: {send_whatsapp:send_whatsapp},
        url: base_url + '/print_purchase_challan/' + $('#purchase_challan_id').val() + '?send_sms=' + send_sms,
        success: function (data) {
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                location.reload();
            };
            location.reload();
        }
    });
});
/*
 * set purchase advice id for print form
 * @param {type} purchase_advice_id
 * @returns {integer}
 */
function print_purchase_advice(purchase_advice_id,vehicle_number) {
    $('#pa_id').val(purchase_advice_id);
    $('#vehicle_no').val(vehicle_number);
    if($('#vehicle_no').hasClass('error_validation')){        
        $('#vehicle_no').removeClass('error_validation');
    }
}
/*
 * print purchase advice
 */
$('.print_purchase_advise').click(function () {    
    var vehicle_number = $('#vehicle_no').val()
    if(vehicle_number ==""){            
        $('#vehicle_no').addClass('error_validation');
        return false;
    }    
    var base_url = $('#baseurl').attr('name');
    var send_sms = '';
    var id = 0;
    id = $('#pa_id').val();
    if ($("#checksms").is(':checked'))
        send_sms = true;  // checked
    else
        send_sms = false;  // unchecked
    if ($("#checkwhatsapp").is(':checked'))
        send_whatsapp = true;  // checked
    else
        send_whatsapp = false;  // unchecked
    if (id != 0) {

        $.ajax({
            type: "GET",
            data: {send_whatsapp:send_whatsapp,vehicle_number:vehicle_number},
            url: base_url + '/print_purchase_advise/' + id + '?send_sms=' + send_sms,
            success: function (data) {
                var printWindow = window.open(data);
                // var printWindow = window.open('', '');
                printWindow.document.write(data);
                printWindow.print();
                printWindow.close();
                printWindow.onunload = function () {
                    location.reload();
                };
                location.reload();
            }
        });
    }
});

$('.print_purchase_daybook').click(function () {
    var base_url = $('#baseurl').attr('name');
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_daybook',
        success: function (data) {
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                location.reload();
            };
            location.reload();
        }
    });
});

$('.print_purchase_estimate').click(function () {
    var base_url = $('#baseurl').attr('name');
    $.ajax({
        type: "GET",
        url: base_url + '/print_purchase_estimate',
        success: function (data) {
            var printWindow = window.open(data);
            // var printWindow = window.open('', '');
            printWindow.document.write(data);
            printWindow.print();
            printWindow.close();
            printWindow.onunload = function () {
                location.reload();
            };
            location.reload();
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