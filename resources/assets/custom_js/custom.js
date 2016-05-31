$(document).ready(function () {

    window.setTimeout(function () {
        $(".alert-autohide").fadeTo(1500, 0).slideUp(500, function () {
        });
    }, 5000);
    $('body').on('click', '#demodiv', function () {
        var baseurl = $('#baseurl').attr('name');
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType: 'jsonp',
            url: baseurl + "/postdemo",
            success: function (data) {
                console.log(data);
            }
        });
    });
    $("#product_type2").click(function () {
        $(".thick").hide();
    });
    $("#product_type1").click(function () {
        $(".thick").show();
    });
    $('#product_sub_category_select').change(function () {

        var prod = $('#product_sub_category_select').val();
        if (prod == 1) {
            $('.thick12').css('display', 'block');
        }

        if (prod == 2) {
            $('.thick12').css('display', 'none');
        }

        var product_type_id = $("#product_sub_category_select").val();
        var url = $('#baseurl2').val();
        var token = $('#_token').val();
        $.ajax({
            type: 'get',
            url: url + '/get_product_category',
            data: {product_type_id: product_type_id, _token: token},
            success: function (data) {
                var main_array = JSON.parse(data);
                var prod = main_array['prod'];
                var str = '';
                var str2 = '<option value=""> --select-- </option>';
                for (var key in prod) {
                    str += '<option value="' + prod[key].id + '"> ' + prod[key].product_category_name + ' </option>';
                }

                $('#select_product_categroy').html(str);
            }
        });
    });
});
$(document).ready(function () {
    $("#exist_customer").click(function () {
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#new_customer").click(function () {
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    $("#optionsRadios4").click(function () {
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
});
$('#datepickerDate').datepicker({
    format: 'mm-dd-yyyy',
    startDate: new Date(),
    autoclose: true

});
$('#datepickerDateComponent').datepicker();
$('#datepickerDate1').datepicker({
    format: 'mm-dd-yyyy',
    startDate: new Date(),
    autoclose: true
});
$('#datepickerDateComponent1').datepicker();
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
    $("#add_product_row_delivery_challan").on("click", function () {
        var current_row_count = $(".add_product_row").length + 1;
        var baseurl = $('#baseurl').attr('name');
        $.ajax({
            type: "GET",
            url: baseurl + '/get_units'
        }).done(function (data) {
            var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
            }
            $("#units_" + current_row_count).html(html);
        });
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-2">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control each_product_detail" data-productid="' + current_row_count + '" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="delivery_challan_product_name_' + current_row_count + '" onfocus="delivery_challan_product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" id="product_weight_' + current_row_count + '" value="">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="actual_quantity_' + current_row_count + '" class="form-control" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="fetch_price();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input id="actual_pieces_' + current_row_count + '" class="form-control calc_actual_quantity" placeholder="Actual Pieces" name="product[' + current_row_count + '][actual_pieces]" value="" type="text" onblur="fetch_price();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input id="present_shipping_' + current_row_count + '" class="form-control text-center" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<div id="amount_' + current_row_count + '">0</div>' +
                '</div>' +
                '</td>' +
                '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
                '</tr>';
        $("#add_product_table_delivery_challan").children("tbody").append(html);
    });
    $('.delete_completed').on('click', function () {
        if (this.checked) {
            $('.checkBoxClass').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkBoxClass').each(function () {
                this.checked = false;
            });
        }
    });
    $('.checkBoxClass').on('click', function () {
        if ($('.checkBoxClass:checked').length == $('.checkBoxClass').length) {
            $('.delete_completed').prop('checked', true);
        } else {
            $('.delete_completed').prop('checked', false);
        }
    });
    $('.save_all_inventory').on("click", function (e) {
        $('#frm_inventory_save_all').submit();
    });
    $('.delete_records_modal').on("click", function (e) {
        if ($('#password_delete_completetd').val().trim().length == 0) {
            $('.delete_records_empty').text("Please enter your password");
            $('.delete_records_empty').css("display", "block");
            $('.delete_records_empty').css("opacity", "1");
            $('.delete_records_empty').focus();
        } else {
            $('#password_delete').val($('#password_delete_completetd').val());
            $('#frmdeleterecords').submit();
        }
    });
    $('.submit_delete_all').on("click", function (e) {
        e.preventDefault();
        var checkedAtLeastOne = false;
        $('.checkBoxClass').each(function () {
            if ($(this).is(":checked")) {
                checkedAtLeastOne = true;
            }
        });
//        $('#password_delete_completetd').val().trim().length
//            $('#empty_select_completed').text("Please enter your password");
//            $('#empty_select_completed').css("display", "block");
//            $('#empty_select_completed').css("opacity", "1");
//            $('#password_delete_completetd').focus();
//            window.setTimeout(function () {
//                $('#empty_select_completed').fadeTo(1500, 0).slideUp(500, function () {
//                });
//            }, 5000);
//        } 
        if (checkedAtLeastOne) {
            $('#delete_records_modal').modal('show');
//            $(this).closest("form").unbind("submit");
//            $(this).closest("form").submit();
        } else {
            $('#empty_select_completed').text("Please select atleast one record from the list");
            $('#empty_select_completed').css("display", "block");
            $('#empty_select_completed').css("opacity", "1");
            window.setTimeout(function () {
                $('#empty_select_completed').fadeTo(1500, 0).slideUp(500, function () {
                });
            }, 5000);
        }
    });
    $('#add_more_product').on("click", function () {
        var current_row_count = $(".add_product_row").length + 1;
        $.ajax({
            type: "GET",
            url: baseurl + '/get_units'
        }).done(function (data) {
            var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
            }
            $("#units_" + current_row_count).html(html);
        });
        var str = ' <tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '    <td>' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control each_product_detail" data-productid="' + current_row_count + '"  placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
                '<input type="hidden" name="product[' + current_row_count + '][product_category_id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '    </td>' +
                '    <td>' +
                '        <div class="form-group">' +
                '            <input id="actual_quantity_' + current_row_count + '" class="form-control" placeholder="Actual Quantity" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="purchase_challan_calculation();">' +
                '        </div>' +
                '    </td>' +
                '    <td>' +
                '        <div class="form-group">' +
                '           <select class="form-control" name="product[' + current_row_count + '][unit_id]" id="units_' + current_row_count + '">' +
                '               ' +
                '           </select>' +
                '        </div>' +
                '    </td>  ' +
                '    <td>  ' +
                '        <div class="form-group">' +
                '            <input id="shipping" class="form-control" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">' +
                '        </div>' +
                '    </td>' +
                '    <td class="shippingcolumn">' +
                '        <div class="row ">' +
                '            <div class="form-group col-md-12">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]" onblur="purchase_challan_calculation();">' +
                '            </div>' +
                '        </div>' +
                '    </td>' +
                '    <td>   ' +
                '        <div class="form-group">' +
                '            <div id="amount_' + current_row_count + '"></div>' +
                '        </div>' +
                '    </td>' +
                '</tr>';
        $("#table-example").children("tbody").append(str);
    });
});
$(function () {
    $('.smstooltip').tooltip();
});
$(function () {
    $('.menutooltip').tooltip();
});
/**
 * Comment
 */
function create_purchase_challan_function() {

    var current_row_count = $(".add_product_row").length + 1;
    $.ajax({
        type: "GET",
        url: baseurl + '/get_units'
    }).done(function (data) {
        var main_array = JSON.parse(data);
        var arr1 = main_array['units'];
        var html = '';
        for (var key in arr1) {
            html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
        }
        $("#units_" + current_row_count).html(html);
    });
    var str = ' <tr id="add_row_' + current_row_count + '" class="add_product_row">' +
            '    <td>' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" data-productid="' + current_row_count + '" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][product_category_id]" id="add_product_id_' + current_row_count + '">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '    </td>' +
            '    <td>' +
            '        <div class="form-group">' +
            '            <input id="actual_quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Actual Quantity" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="purchase_challan_calculation();">' +
            '        </div>' +
            '    </td>' +
            '    <td>' +
            '        <div class="form-group">' +
            '           <select class="form-control" name="product[' + current_row_count + '][unit_id]" id="units_' + current_row_count + '">' +
            '               ' +
            '           </select>' +
            '        </div>' +
            '    </td>  ' +
            '    <td>  ' +
            '        <div class="form-group">' +
            '            <input id="shipping_' + current_row_count + '" class="form-control" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">' +
            '        </div>' +
            '    </td>' +
            '    <td class="shippingcolumn">' +
            '        <div class="row ">' +
            '            <div class="form-group col-md-12">' +
            '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]" onblur="purchase_challan_calculation();">' +
            '            </div>' +
            '        </div>' +
            '    </td>' +
            '    <td>   ' +
            '        <div class="form-group">' +
            '            <div id="amount_' + current_row_count + '"></div>' +
            '        </div>' +
            '    </td>' +
            '</tr>';
    $("#table-example").children("tbody").append(str);
}

/**
 * calutate_pending_order
 */
function calutate_pending_order(qty, key) {

    var shipping = $('#present_shipping_' + key).val();
    var pending = $('#pending_order_org' + key).val();
    if (parseInt(shipping) <= parseInt(qty)) {
        $('#pending_order_' + key).val(qty - shipping);
    } else {
//        alert('Present shipping should not be greater than pending order');// Commented by amit on 29-09-2015 to allow shipping > actual quantity
//        $('#present_shipping_' + key).val("");
        $('#pending_order_' + key).val('0');
    }
}

function state_option() {

    var state = $("#state").val();
    var site_url = $('#site_url').val();
    $.ajax({
        type: "GET",
        url: site_url + '/get_city',
        data: {state: state},
    }).done(function (data) {
        var main_array = JSON.parse(data);
        var city = main_array['city'];
        var str = '';
        var str = '<option value="" selected=""> --Select City-- </option>';
        for (var key in city) {
            str += '<option value="' + city[key].id + '"> ' + city[key].city_name + ' </option>';
        }
        $('#city').html(str);
    });
}

function update_price(product_id) {

    var price = $('#price_' + product_id).val();
    var url = $('#site_url').val();
    var token = $('#token').val();
    $.ajax({
        type: "GET",
        url: url + '/update_price',
        data: {price: price, product_id: product_id, _token: token},
    }).done(function (data) {
        $('.alert-success1').show();
    });
}


$('#onenter_prevent input').keypress(function (e) {
    if (e.which == 13) {
        return false;
    }
});
$('body').delegate("#add_order_location", "blur", function () {
    if ($(this).val() == '0') {
        $(this).addClass('error_validation');
    } else {
        $(this).removeClass('error_validation');
    }
});
$('body').delegate(".btn_add_inquiry, .btn_add_inquiry_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#customer_name').val() == "") {
            $('#customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
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
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_edit_inquiry, .btn_edit_inquiry_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == "") {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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
            if ($("#add_product_id_0").val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "edit_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_inquiry_location').val() == '0') {
            $('#add_inquiry_location').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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
            if ($("#add_product_id_0").val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "edit_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_add_order, .btn_add_order_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
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
                if ($("#add_product_id_" + i).val() == "" || $('#existing_customer_name').val() == "") {
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
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_edit_order, .btn_edit_order_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
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
                if ($("#add_product_id_" + i).val() == "" || $('#existing_customer_name').val() == "") {
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
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#product_price_" + i).val() == "") {
                    $('#product_price_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
//        if (j == tot_products) {
//            if ($("#add_product_id_1").val() == "") {
//                $('#add_product_name_1').addClass('error_validation');
//            }
//            if ($("#quantity_1").val() == "") {
//                $('#quantity_1').addClass('error_validation');
//            }
//            status_form = 1;
//        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "edit_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_edit_delivery_order", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products + 2; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#existing_customer_name').val() == "") {
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
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products + 1; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#product_price_" + i).val() == "") {
                    $('#product_price_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            $(this).parents('form').submit();
        }
    }

});
$('body').delegate(".btn_add_delivery_order", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
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

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            $('#onenter_prevent').submit();
        }

    } else {
        if ($('#existing_customer_id').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
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
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            $('#onenter_prevent').submit();
        }
    }

});
$('body').delegate(".btn_puradvice_to_purchallan", "click", function () {

    var status_form = 0;
    var tot_products = $(".add_product_row").length;
    var j = 0;
    for (i = 0; i <= tot_products; i++) {
        if (($("#add_product_id_" + i).val() == "") && ($("#actual_quantity_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "") {
                $('#add_product_name_' + i).addClass('error_validation');
                status_form = 1;
            } else {
                if ($('#add_purchase_product_name_' + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#shipping_" + i).val() == "") {
                    $('#shipping_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#actual_quantity_" + i).val() == "") {
                    $('#actual_quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#product_price_" + i).val() == "") {
                    $('#product_price_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
    }
    if ($('#freight').val() == "") {
        $('#freight').addClass('error_validation');
        status_form = 1;
    }
    if ($('#loadedby').val() == "") {
        $('#loadedby').addClass('error_validation');
        status_form = 1;
    }
    if ($('#labour').val() == "") {
        $('#labour').addClass('error_validation');
        status_form = 1;
    }
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_purorder_to_puradvice", "click", function () {
    var status_form = 0;
    var tot_products = $(".add_product_row").length;
    var j = 0;
    if ($("#vehicle_number").val() == "") {
        $("#vehicle_number").addClass('error_validation');
        status_form = 1;
    }
    if ($("#datepickerDate").val() == "") {
        $("#datepickerDate").addClass('error_validation');
        status_form = 1;
    }

    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_delorderto_delchallan", "click", function () {
    var status_form = 0;
    var tot_products = $(".add_product_row").length;
    var j = 0;
    for (i = 1; i <= tot_products; i++) {
        if (($("#add_product_id_" + i).val() == "") && ($("#actual_quantity_" + i).val() == "")) {
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
            if ($("#present_shipping_" + i).val() == "") {
                $('#present_shipping_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#actual_quantity_" + i).val() == "") {
                $('#actual_quantity_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#product_price_" + i).val() == "") {
                $('#product_price_' + i).addClass('error_validation');
                status_form = 1;
            }
        }
    }
//    if (j == tot_products) {
//        for (i = 0; i <= tot_products; i++) {
//            $('#add_product_name_' + i).addClass('error_validation');
//        }
//        status_form = 1;
//    }
//    alert(status_form);
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_order_to_delorder", "click", function () {

    var status_form = 0;
//    if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
//        $('#existing_customer_name').addClass('error_validation');
//        status_form = 1;
//    }
//    if ($('#add_order_location').val() == '0') {
//        $('#add_order_location').addClass('error_validation');
//        status_form = 1;
//    }
    var tot_products = $(".add_product_row").length;
    var j = 0;
    for (i = 0; i <= tot_products + 1; i++) {
        if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
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
            if ($("#present_shipping_" + i).val() == "") {
                $('#present_shipping_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#quantity_" + i).val() == "") {
                $('#quantity_' + i).addClass('error_validation');
                status_form = 1;
            }
        }
    }
//    if (j == tot_products) {
//        for (i = 0; i <= tot_products+1; i++) {
//            $('#add_product_name_' + i).addClass('error_validation');
//        }
//        status_form = 1;
//    }
//    alert(status_form);
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_inquiry_to_order, .btn_inquiry_to_order_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
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
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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
            if ($("#add_product_id_0").val() == "" || $('#add_product_name_0').val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_to_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
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
            if ($("#add_product_id_0").val() == "" || $('#add_product_name_0').val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_to_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$("#product_size").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function (request, response) {
        $("#product_size").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_product_size',
            data: {"term": request.term},
            success: function (data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#product_size").removeClass('loadinggif');
            },
        });
    },
    select: function (event, ui) {
        $("#product_size").val(ui.item.id);
    }

});
$("#order_size").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function (request, response) {
        $("#order_size").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_product_size',
            data: {"term": request.term},
            success: function (data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#order_size").removeClass('loadinggif');
            },
        });
    },
    select: function (event, ui) {
        $("#order_size").val(ui.item.value);
        $("#order_size_temp").val(ui.item.id);
    }

});
$("#search_text").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function (request, response) {
        $("#search_text").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_product_name',
            data: {"term": request.term},
            success: function (data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#search_text").removeClass('loadinggif');
            },
        });
    },
    select: function (event, ui) {
        $("#search_text").val(ui.item.id);
    }
});
$('#save_all_price_btn').click(function () {

    $.ajax({
        type: 'post', url: baseurl + '/update_all_price',
        data: $('#save_all_price').serialize(),
        success: function (data) {
            $('html,body').animate({scrollTop: 0}, 'slow');
            $('.alert-success1').show();
        }
    });
});
$("#search_inventory").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function (request, response) {
        $("#search_inventory").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_inventory_product_name',
            data: {"term": request.term},
            success: function (data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#search_inventory").removeClass('loadinggif');
            },
        });
    },
    select: function (event, ui) {
        $("#search_inventory").val(ui.item.label);
    }
});
function isNumber(evt, element) {

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (
            (charCode != 45 || $(element).val().indexOf('-') != -1) && // “-” CHECK MINUS, AND ONLY ONE.
            (charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function test() {
//    var abc = $(e).val();
//    $(e).val(function(i, abc) {
//        return abc.replace(/\d{3}|[^\d{2}\.]|^\./g, "");
//    });
    return isNumber(event, this);
}

function isNumberFormat(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31
            && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function submit_filter_form() {
    $("#searchCustomerForm").submit();
}

$('#labour').on('keyup', function (e) {
    if (e.which === 46)
        return false;
}).on('input', function () {
    var self = this;
    setTimeout(function () {
        if (self.value.indexOf('.') != -1)
            self.value = parseInt(self.value, 10);
    }, 0);
});
$('#delete_records_modal').on('hidden.bs.modal', function () {
    $('#password_delete_completetd').val();
    $('.delete_records_empty').css('display', 'none');
});
$('#save_all_size_btn').click(function () {
    var token = $('#_token').val();
    var pageid = $(this).attr('data-pageid');
    $.ajax({
        type: 'POST',
        url: baseurl + '/update_all_sizes',
        data: {form_data: $('#save_all_product_sizes').serialize(), _token: token, pageid: pageid},
        success: function (data) {
            $('.alert-success1').show();
            $('html, body').animate({
                scrollTop: $('.navbar-brand').offset().top
            }, 1000);
        }
    });
});
$('body').delegate(".pendingpadvice", "click", function () {
    var column_name = $(this).attr("data-column");
    var url = $('#base_url').val();
    var sortfield = $('#pending_advice_sortfield').val();
    var sortfieldby = $('#pending_advice_sortfieldby').val();
    if (sortfieldby == "") {
        $('#redirect_url_for_sorting').attr("href", url + "/pending_purchase_advice?filteron=" + column_name + "&filterby=asc");
    } else {
        if (sortfieldby == "desc") {
            $('#redirect_url_for_sorting').attr("href", url + "/pending_purchase_advice?filteron=" + column_name + "&filterby=asc");
        } else {
            $('#redirect_url_for_sorting').attr("href", url + "/pending_purchase_advice?filteron=" + column_name + "&filterby=desc");
        }
    }
    $('#redirect_url_for_sorting').trigger("click");
});
$('body').delegate("#redirect_url_for_sorting", "click", function () {
    var href = $('#redirect_url_for_sorting').attr("href");
    window.parent.location.href = href;
});
$('body').delegate("#existing_customer_name", "blur", function () {
    var cur_customer_tally_name = $('#existing_customer_id').val();
    if (cur_customer_tally_name == "") {
        $(this).focus();
        $(this).addClass('error_validation');
    } else {
        $(this).removeClass('error_validation');
    }

    if ($('#add_order_location').val() != '0') {
        $('#add_order_location').removeClass('error_validation');
    }
});
$('body').delegate("#name", "blur", function () {
    if ($('#name').val() == "") {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClaaa('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
        $(this).removeClass('error_validation');
    }
});
$('body').delegate("#existing_supplier_name", "blur", function () {
    if (($('#existing_supplier_name').val() == "") || ($('#existing_supplier_id').val() == "")) {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClaaa('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
    }
});
$('body').delegate(".each_product_qty", "blur", function () {
    var cur_product_id = $(this).attr("data-productid");
    if ($('#add_product_name_' + cur_product_id).val() != "") {
        if ($('#quantity_' + cur_product_id).val() == "") {
            $(this).focus();
            $(this).addClass('error_validation');
        } else {
            $(this).removeClass('error_validation');
        }
    }
});
$('body').delegate(".each_product_detail", "blur", function () {
    var current_product = $(this).val()
    var cur_product_id = $(this).attr("data-productid");
    if (current_product == "") {
//        $(this).focus();
//        $(this).css('border-color', 'red');

        $('#add_product_id_' + cur_product_id).val('');
        $('#add_product_id_' + cur_product_id).attr('data-curname', '');
    } else {

        var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
        if (related_cur_product_id == "") {
            $(this).focus();
            $(this).css('border-color', 'red');
            $(this).css('box-shadow', 'none');
            $(this).addClass('error_validation');
        } else {
            $(this).css('border-color', '#e7ebee');
            $(this).removeClass('error_validation');
        }
    }
});
$(".no_alphabets").keydown(function (event) {
    if ((event.keyCode >= 65) && (event.keyCode <= 90)) {
        return false;
    }
});
$('body').delegate(".each_product_detail_edit", "blur", function () {
    var current_product = $(this).val()
    var cur_product_id = $(this).attr("data-productid");
    var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
    if (related_cur_product_id == "") {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClass('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
        $(this).removeClass('error_validation');
    }
});
$('body').delegate(".pendingorder", "click", function () {
    var column_name = $(this).attr("data-column");
    var url = $('#base_url').val();
    var sortfield = $('#pending_order_sortfield').val();
    var sortfieldby = $('#pending_order_sortfieldby').val();
    if (sortfieldby == "") {
        $('#redirect_url_for_sorting').attr("href", url + "/pending_delivery_order?filteron=" + column_name + "&filterby=asc");
    } else {
        if (sortfieldby == "desc") {
            $('#redirect_url_for_sorting').attr("href", url + "/pending_delivery_order?filteron=" + column_name + "&filterby=asc");
        } else {
            $('#redirect_url_for_sorting').attr("href", url + "/pending_delivery_order?filteron=" + column_name + "&filterby=desc");
        }
    }
    $('#redirect_url_for_sorting').trigger("click");
});
function update_difference(e) {
    var difference = $(e).parent().parent().children().find("input[type=tel]").val();
    var id = $(e).parent().parent().children().find("input[type=hidden]").val();
    var token = $('#_token').val();
    $.ajax({
        type: 'post',
        url: baseurl + '/update_difference',
        data: {difference: difference, id: id, _token: token},
        success: function (data) {
//            $('.custom_alert_success').fadeOut(5000);
            $('.alert-success1').show();
            $('html, body').animate({
                scrollTop: $('.navbar-brand').offset().top
            }, 1000);
        }
    });
}
function update_inventory(e, value) {
    $('.inventory_update_min,.inventory_update_max,.inventory_update').css('display', 'none');
    var id = value;
    var opening_stock = $("input[name=" + id + "]").val();
    var minimal = $('#minimal_' + id).val();
    if (opening_stock < 0) {
        $('.inventory_update_min').css('display', 'block');
        $('.inventory_update_min').css('opacity', 1);
        window.setTimeout(function () {
            $(".inventory_update_min").fadeTo(1500, 0).slideUp(500, function () {
            });
        }, 5000);
    } else if (minimal < 0) {
        $('.inventory_update_min').html('<strong>Error!</strong> Negatives values are not allowed in Minimum stock.');
        $('.inventory_update_min').css('display', 'block');
        $('.inventory_update_min').css('opacity', 1);
        window.setTimeout(function () {
            $(".inventory_update_min").fadeTo(1500, 0).slideUp(500, function () {
            });
        }, 5000);
    } else {
        var result = opening_stock.split(".");
        if (result[0].length > 6) {
            $('.inventory_update_max').css('display', 'block');
            $('.inventory_update_max').css('opacity', 1);
            window.setTimeout(function () {
                $(".inventory_update_max").fadeTo(1500, 0).slideUp(500, function () {
                });
            }, 5000);
        } else {
            $.ajax({
                type: 'get',
                url: baseurl + '/update_inventory',
                data: {opening_stock: opening_stock, minimal: minimal, id: id},
                success: function (data) {
                    var response_array = data;
                    var response_array = JSON.parse(data);
                    if (response_array['class'] == 'yes') {
                        $('#minimal_' + id).parent().parent().addClass('minimum_reach');
                    } else {
                        $('#minimal_' + id).parent().parent().removeClass('minimum_reach');
                    }
                    $('#sales_challan_' + id).text(response_array['sales_challan_qty']);
                    $('#purchase_challan_' + id).text(response_array['purchase_challan_qty']);
                    $('#physical_closing_' + id).text(response_array['physical_closing_qty']);
                    $('#pending_order_' + id).text(response_array['pending_sales_order_qty']);
                    $('#pending_deliver_order_' + id).text(response_array['pending_delivery_order_qty']);
                    $('#pending_purchase_order_' + id).text(response_array['pending_purchase_order_qty']);
                    $('#pending_purchase_advise_' + id).text(response_array['pending_purchase_advise_qty']);
                    $('#virtual_qty_' + id).text(response_array['virtual_qty']);
                    $('.datadisplay_' + id).effect('highlight', {color: "yellow"}, 5000);
                    $('.inventory_update').css('display', 'block');
                    $('.inventory_update').css('opacity', 1);
                    window.setTimeout(function () {
                        $(".inventory_update").fadeTo(1500, 0).slideUp(500, function () {
                        });
                    }, 4000);
                }
            });
        }

    }



    $('html, body').animate({
        scrollTop: $('.navbar-brand').offset().top
    }, 1000);
}