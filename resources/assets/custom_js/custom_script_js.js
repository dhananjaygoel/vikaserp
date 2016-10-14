$(document).ready(function () {
    $('form').attr('autocomplete', 'off');
    
    $("#warehouse_radio").click(function () {
        $(".supplier_order").hide();
    });
    $("#supplier_radio").click(function () {
        $(".supplier_order").show();
    });
    $("#existing_customer").click(function () {
        $(".new_customer_details").hide();
        $(".customer_select_order").show();
    });
    $("#new_customer").click(function () {
        $(".new_customer_details").show();
        $(".customer_select_order").hide();
    });
    $("#vat_inclusive").click(function () {
        $(".vat_field").show();

    });
    $("#all_inclusive").click(function () {
        $(".vat_field").hide();
    });
    $('#add_order_location').change(function () {
        if ($('#add_order_location').val() == 'other') {
            $('.locationtext').show();
        } else {
            $('.locationtext').hide();
        }
    });
    $('#add_inquiry_location').change(function () {
        if ($('#add_order_location').val() == '-2') {
            $('.locationtext').toggle();
            $('.other_location_input_wrapper').toggle();
        }
    });
    $('#purchase_other_location').change(function () {
        if ($('#purchase_other_location').val() == '-1') {
            $('#other_location_input_wrapper').css('display', 'block');
        } else {
            $('#other_location_input_wrapper').css('display', 'none');
        }
    });

    /**
     * Comment
     */
    function show_hide_supplier(status) {
        if (status == "warehouse") {
            $(".supplier").hide();
        }
        else {
            if (status == 'supplier') {
                $(".supplier").hide();
            }
        }
    }

    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    $('#expected_delivery_date_order').datepicker({
        format: 'mm-dd-yyyy',
        startDate: new Date(),
        autoclose: true
    });
    $('#estimated_delivery_date').datepicker({
        format: 'mm-dd-yyyy',
        startDate: new Date(),
        autoclose: true
    });
    $('#sales_daybook_date').datepicker({
//        format: 'dd-mm-yyyy',
//        startDate: new Date(),
//        autoclose: true
        format: 'dd-mm-yyyy',
        autoclose: false
    });

//    $("#add_product_row_delivery_challan").on("click", function () {
//        var current_row_count = $(".add_product_row").length + 1;
//        $.ajax({
//            type: "GET",
//            url: baseurl + '/get_units'
//        }).done(function (data) {
//            var main_array = JSON.parse(data);
//            var arr1 = main_array['units'];
//            var html = '';
//            for (var key in arr1) {
//                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
//            }
//            $("#units_" + current_row_count).html(html);
//        });
//        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
//                '<td class="col-md-2">' +
//                '<div class="form-group searchproduct">' +
//                '<input class="form-control each_product_detail" data-productid="'+ current_row_count +'" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
//                '<input type="hidden" id="product_weight_' + current_row_count + '" value="">' +
//                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
//                '<i class="fa fa-search search-icon"></i>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-1">' +
//                '<div class="form-group">' +
//                '<input id="actual_quantity_' + current_row_count + '" class="form-control" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="fetch_price();">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<input id="actual_pieces_' + current_row_count + '" class="form-control calc_actual_quantity" placeholder="Actual Pieces" name="product[' + current_row_count + '][actual_pieces]" value="" type="text" onblur="fetch_price();">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<input id="present_shipping_' + current_row_count + '" class="form-control text-center" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group ">' +
//                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
//                '</select>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<div id="amount_' + current_row_count + '">0</div>' +
//                '</div>' +
//                '</td>' +
//                '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
//                '</tr>';
//        $("#add_product_table_delivery_challan").children("tbody").append(html);
//    });

//    $("#add_product_row_delivery_order").on("click", function () {
//        var current_row_count = $(".add_product_row").length + 2;
//        $.ajax({
//            type: "GET",
//            url: baseurl + '/get_units'
//        }).done(function (data) {
//            var main_array = JSON.parse(data);
//            var arr1 = main_array['units'];
//            var html = '';
//            for (var key in arr1) {
//                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
//            }
//            $("#units_" + current_row_count).html(html);
//        });
//        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
//                '<td class="col-md-2">' +
//                '<div class="form-group searchproduct">' +
//                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
//                '<input type="hidden" name="product[' + current_row_count + '][product_category_id]" id="add_product_id_' + current_row_count + '">' +
//                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
//                '<i class="fa fa-search search-icon"></i>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-1">' +
//                '<div class="form-group">' +
//                '<input id="quantity_' + current_row_count + '" class="form-control dileep" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="create_delivery_order_PS(' + current_row_count + ');">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group ">' +
//                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
//                '</select>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-1">' +
//                '<div class="form-group">' +
//                '<input id="present_shipping_' + current_row_count + '" class="form-control" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text" onblur="change_quantity(' + current_row_count + ');">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]" onblur="grand_total_delivery_order();">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-1">' +
//                '<div class="form-group">' +
//                '<input id="pending_qunatity_value_' + current_row_count + '" class="form-control text-center" name="product[' + current_row_count + '][pending_quantity]" value="" type="hidden">' +
//                '<div id="pending_qunatity_' + current_row_count + '"></div>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
//                '</div>' +
//                '</td>' +
//                '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
//                '</tr>';
//        $("#add_product_table_delivery_order").children("tbody").append(html);
//    });

    challan_ids = [];

    if ($("#add_product_table_delivery_challan").length > 0) {
        fetch_price();
    }
    grand_total_challan();
});
/**
 * Comment
 */
function select_all_checkbox() {

    if ($('#select_all_button').attr('all_checked') == 'allunchecked') {
        $(':checkbox').each(function () {
            this.checked = true;
        });
        $('#select_all_button').attr({'all_checked': 'allchecked'});
    } else {
        if ($('#select_all_button').attr('all_checked') == 'allchecked') {
            $(':checkbox').each(function () {
                this.checked = false;
            });
            $('#select_all_button').attr({'all_checked': 'allunchecked'});
        }
    }
}

function getunit_name(key) {
    var unit_name = $("#units_" + key).value();
    alert(unit_name);
}

/**
 * Calculate quantity on
 * on present shiping
 */
function change_quantity(key) {

    var quantity = $("#pending_qunatity_value_" + key).val();
    var present_shipping = $("#present_shipping_" + key).val();
    var tot_quty = $("#quantity_" + key).val(); //ok
    var total = parseInt(quantity) + parseInt(present_shipping);
    if (parseInt(present_shipping) > parseInt(tot_quty)) {
//        alert('present Shipping is greater than the quantity');// Commented by amit on 29-09-2015 to allow shipping > actual quantity
//        $("#present_shipping_" + key).val(tot_quty);
        $("#present_shipping_" + key).val(parseInt(present_shipping));
        $("#pending_qunatity_" + key).html("<span>" + 0 + "</span");
    } else {
        $("#pending_qunatity_" + key).html("<span>" + (parseInt(tot_quty) - parseInt(present_shipping)) + "</span");
    }
    grand_total_delivery_order();
}

function change_quantity2(key) {

    var quantity = $("#pending_qunatity_value_" + key).val();
    var present_shipping = $("#present_shipping_" + key).val();
    var tot_quty = $("#quantity_" + key).val(); //ok
    var total = parseInt(quantity) + parseInt(present_shipping);
    $("#present_shipping_" + key).val(present_shipping);
    if ((parseInt(tot_quty) - parseInt(present_shipping) < 0) || isNaN(parseInt(tot_quty) - parseInt(present_shipping)) == true) {
        $("#pending_qunatity_" + key).html("<span>" + 0 + "</span");
    } else {
        $("#pending_qunatity_" + key).html("<span>" + (parseInt(tot_quty) - parseInt(present_shipping)) + "</span");
    }
    grand_total_delivery_order();
}

/**
 * Change Amount
 */
function product_rate(key) {

    var product_id = $("#add_product_id_" + key).val();
    var customer_id = $("#customer_id").val();
    $.ajax({
        url: baseurl + '/fetch_product_price',
        data: {"customer_id": customer_id, "product_id": product_id
        },
        success: function (data) {
            var main_array = JSON.parse(data);
            var arr1 = main_array['data_array'];
            var product_price = arr1[0].product_price;
            var product_difference = arr1[0].product_difference;
            var customer_difference = arr1[0].customer_difference;
            var rate = parseInt(product_price) + parseInt(product_difference) + parseInt(customer_difference);
            $("#product_price_" + key).val(rate);
        }
    });
}

/**
 * Grand total for Delivery order
 */

function grand_total_delivery_order() {

    var current_row_count = $(".add_product_row").length;
    var total_price = 0;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseInt($('#product_price_' + i).val())) {
            var present_shipping = parseInt($("#present_shipping_" + i).val());
            total_price = total_price + (parseInt($('#product_price_' + i).val()) * present_shipping);
        }
    }
    var vat_val = 0;
    if ($('#optionsRadios6').is(':checked')) {
        vat_val = (parseInt(total_price) * $('#vat_percentage').val()) / 100;
    }
    if ($('#optionsRadios5').is(':checked')) {
        vat_val = 0;
    }
    var grand_total = parseInt(total_price) + parseInt(vat_val);
    if ($("#total_price").length > 0) {
        $("#total_price").val(total_price);
    }
    if ($("#discount_value").length > 0) {
        if ($("#discount_value").val() > 0) {
            var discount_value = (parseFloat($("#discount_value").val()) * total_price) / 100;
            grand_total = grand_total - discount_value;
        }
    }
    if ($("#freight_value").length > 0) {
        if ($("#freight_value").val() > 0) {
            var freight_value = $("#freight_value").val();
            total_price = total_price + freight_value;
        }
    }
    if ($("#loading_charge").length > 0) {
        if ($("#loading_charge").val() > 0) {
            var loading_charge = parseInt($("#loading_charge").val());
            grand_total = grand_total + loading_charge;
        }
    }
    $('#grand_total').val(grand_total);
}

/**
 * Fetch price of the product
 */
function fetch_price() {

    var current_row_count = $(".add_product_row").length;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseFloat($('#product_price_' + i).val())) {
            var quantity = $("#actual_quantity_" + i).val();
            if (quantity > 0) {
                /*
                 * Calculate checking wih KG and other in
                 * quantity field
                 */
                if ($("#actual_quantity_" + i).val() > 0 && $("#actual_quantity_" + i).val() != 0 || $("#actual_quantity_" + i).val() != '') {
                    quantity = parseFloat($("#actual_quantity_" + i).val());
                }
            } else {
                if ($("#actual_pieces_" + i).val() > 0 && $("#actual_quantity_" + i).val() == 0 || $("#actual_quantity_" + i).val() == '') {
                    quantity = parseFloat($("#actual_pieces_" + i).val());
                }
            }
            var rate = $("#product_price_" + i).val();
//            var vat_percentage = $("#product_vatpercentage_" + i).val();
//            if (vat_percentage == '') {
//                vat_percentage = 0;
//            }
            var amount = parseFloat(rate) * parseInt(quantity);
//            amount = parseFloat(amount + ((amount * vat_percentage) / 100));
            if (amount > 0) {
                $("#amount_" + i).html('<span class="text-center">' + amount.toFixed(2) + '</span>');
            }
        }
    }
    grand_total_challan();
}
$("body").delegate(".calc_actual_quantity", "keyup", function (event) {

    var rowId = $(this).attr('id').split('actual_pieces_');
    rowId = rowId[1];
    var weight = $('#product_weight_' + rowId).val();
    var actual_pieces = $(this).val();
    if ($('#actual_quantity_' + rowId).val() < 0) {
        if (actual_pieces != '') {
            if (weight != '')
                $('#actual_quantity_' + rowId).val((actual_pieces * weight).toFixed(2));
            else
                $('#actual_quantity_' + rowId).val(actual_pieces);
        }
    }
    fetch_price();
});
/**
 * Grand total for creating independent delivery order
 */
function calculate_grand_total() {
    var current_row_count = $(".add_product_row").length;
    var total_price = 0;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseInt($('#product_price_' + i).val())) {
            var quantity = parseInt($("#quantity_" + i).val());
            total_price = total_price + (parseInt($('#product_price_' + i).val()) * quantity);
        }
    }
}
/**
 * Default Delivery Location for existing customers
 */
function default_delivery_location() {
    var location_id = $('#customer_default_location').val();
    if (location_id > 0) {
        $("#add_inquiry_location").find("option").each(function (i, opt) {
            if (opt.value === location_id)
                $(opt).attr('selected', 'selected');
        });
        $("#add_order_location").find("option").each(function (i, opt) {
            if (opt.value === location_id)
                $(opt).attr('selected', 'selected');
        });
    }
}

/*
 * Function for grand total for challan
 */
function grand_total_challan() {

    var current_row_count = $(".add_product_row").length;
    var total_price_products = 0;
    var total_actual_quantity = 0;
//    var loading_vat_percentage = $('#loading_vat_percentage').val();
//    var freight_vat_percentage = $('#freight_vat_percentage').val();
//    var discount_vat_percentage = $('#discount_vat_percentage').val();
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseFloat($('#product_price_' + i).val())) {
            var unit_id = $("#units_" + i).val();
            var product_id = $("#add_product_id_" + i).val();
            var product_wt;
            var quantity = 0;
            if ($("#actual_quantity_" + i).val() > 0 && $("#actual_quantity_" + i).val() != 0 || $("#actual_quantity_" + i).val() != '') {
                quantity = parseFloat($("#actual_quantity_" + i).val());
            }
//            if ($("#actual_pieces_" + i).val() > 0 && $("#actual_quantity_" + i).val() == 0 || $("#actual_quantity_" + i).val() == '') {
//                quantity = parseFloat($("#actual_pieces_" + i).val());
//            }
//            var vat_percentage = $("#product_vatpercentage_" + i).val();
//            if (vat_percentage == '') {
//                vat_percentage = 0;
//            }
            var amount = parseFloat(parseFloat($('#product_price_' + i).val()) * quantity);
            total_price_products = total_price_products + amount;
            total_actual_quantity = total_actual_quantity + quantity;
        }
    }
    var vat_val = 0;
    var total_price = total_price_products;

    //loading
    var loading_charge = 0;
    if ($("#loading_charge").length > 0) {
        if (parseFloat($("#loading_charge").val())) {
            loading_charge = parseFloat($("#loading_charge").val());
            $("#loading_charge").val(loading_charge.toFixed(2));
            total_price = parseFloat(total_price) + parseFloat(loading_charge.toFixed(2));
        }
//        if (parseFloat(loading_vat_percentage) > 0 && parseFloat(loading_charge) > 0) {
//            var subtotal = ((parseFloat(loading_vat_percentage) * parseFloat($("#loading_charge").val())) / 100);
//            $('#loading_total_charge').attr('value', (parseFloat($("#loading_charge").val()) + subtotal).toFixed(2));
//            total_price += (parseFloat($('#loading_total_charge').attr('value')) - parseFloat($("#loading_charge").val()));
//        } else {
//            if ($("#loading_charge").val().trim() != '') {
//                $('#loading_total_charge').attr('value', $("#loading_charge").val());
//            } else {
//                $('#loading_total_charge').attr('value', '');
//            }
//        }
    }
    //discount
    var discount_value = 0;
    if ($("#discount_value").length > 0) {
        if (parseFloat($("#discount_value").val())) {
            discount_value = parseFloat($("#discount_value").val());
            $("#discount_value").val(discount_value.toFixed(2));
            total_price = parseFloat(total_price) + parseFloat(discount_value);
        }
//        if (parseFloat(discount_vat_percentage) > 0 && parseFloat(discount_value) > 0) {
//            var subtotal_discount = ((parseFloat(discount_vat_percentage) * parseFloat($("#discount_value").val())) / 100);
//            $('#discount_total_charge').attr('value', (parseFloat($("#discount_value").val()) + subtotal_discount).toFixed(2));
//            total_price -= subtotal_discount;
//        } else {
//            if ($("#discount_value").val() != '') {
//                $('#discount_total_charge').attr('value', $("#discount_value").val());
//            } else {
//                $('#discount_total_charge').attr('value', '');
//            }
//        }
    }
//    total_price = parseFloat(total_price) + parseFloat(discount_value.toFixed(2));
//    total_price = total_price.toFixed(2);

//    total_l_d_f
    $("#total_price").val(total_price_products.toFixed(2));
    $("#total_actual_quantity").val(total_actual_quantity.toFixed(2));

    var freight_value = 0;
    if ($("#freight_value").length > 0) {
        if (parseFloat($("#freight_value").val())) {
            freight_value = parseFloat($("#freight_value").val());
            $("#freight_value").val(freight_value.toFixed(2));
            total_price = parseFloat(total_price) + parseFloat(freight_value);
        }
//        if (parseFloat(freight_vat_percentage) > 0 && parseFloat(freight_value) > 0) {
//            var subtotal_frieght = ((parseFloat(freight_vat_percentage) * parseFloat($("#freight_value").val())) / 100);
//            $('#freight_total_charge').attr('value', (parseFloat($("#freight_value").val()) + subtotal_frieght).toFixed(2));
//            total_price += (parseFloat($('#freight_total_charge').attr('value')) - parseFloat($("#freight_value").val()));
//        } else {
//            if ($("#freight_value").val() != '') {
//                $('#freight_total_charge').attr('value', $("#freight_value").val());
//            } else {
//                $('#freight_total_charge').attr('value', '');
//            }
//        }
    }
//    total_price = parseFloat(total_price) + parseFloat(freight_value.toFixed(2));
    total_price = parseFloat(total_price.toFixed(2));
    var vat_val = 0;
    $("#total_l_d_f").html("<span class='text-center'>" + total_price + "</span>");
    if (parseFloat($('#vat_percentage').val()) > 0) {
        vat_val = (total_price * parseFloat($('#vat_percentage').val())) / 100;
        $("#vat_val").html("" + vat_val + "")
    }
    var vat_total = parseFloat(total_price) + parseFloat(vat_val.toFixed(2));
    vat_total = vat_total.toFixed(2);
    $("#vat_tot_val").val(vat_total);
    var round_off = 0;
    if ($('#round_off').val() != '') {
        round_off = parseFloat($("#round_off").val());
    }
    var grand_total = parseFloat(vat_total) + parseFloat(round_off.toFixed(2));
    $('#grand_total').val(grand_total.toFixed(2));
}

/**
 * Calculate total amount value for purchase
 */
function purchase_challan_calculation() {

    var current_row_count = $(".add_product_row").length;
    var total_actual_quantity = 0;
    var total_amount_product = 0;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseFloat($('#product_price_' + i).val())) {
            var quantity = parseFloat($("#actual_quantity_" + i).val());
            var rate = $("#product_price_" + i).val();
            var amount = parseFloat(rate) * parseFloat(quantity);
            $("#amount_" + i).html('' + amount + '');
            total_actual_quantity = total_actual_quantity + parseFloat(quantity);
            total_amount_product = total_amount_product + amount;
        }
    }
    $("#total_price2").html('' + total_amount_product.toFixed(2));
    $("#total_actual_quantity").html('' + total_actual_quantity);
    var discount_value = 0;

    //***************discount
    if ($("#discount").val() != '') {
        discount_value = $("#discount").val();
    }
    discount_value = parseFloat(discount_value);
    var discount = discount_value.toFixed(2);
    $("#discount").val(discount);
    total_price = total_amount_product + parseFloat(discount);
    $("#total_price").html('' + total_price.toFixed(2));
    //**************freight
    var freight_amount = 0;
    if ($("#freight").val() != '') {
        freight_amount = $("#freight").val();
    }
    freight_amount = parseFloat(freight_amount);
    var fre = freight_amount.toFixed(2);
    $("#freight").val(fre);
    tot_frt = total_price + parseFloat(fre);
    $("#total_price").html('' + tot_frt.toFixed(2));

    //*********vat
    var vat_val = 0;
    if ($("#vat_percentage").val() > 0 && $("#vat_percentage").val() != '') {
        vat_val = (tot_frt * parseFloat($('#vat_percentage').val())) / 100;
        $("#vat_value").html('' + vat_val.toFixed(2));
    }
    var vat_total = tot_frt + parseFloat(vat_val.toFixed(2));
    $("#vat_tot_val").val(vat_total.toFixed(2));
    //round off
    var round_off = 0;
    var grand_total = vat_total;
    if ($('#round_off').val() != '') {
        round_off = $("#round_off").val();
        grand_total += parseFloat(round_off);
    }
    $("#grand_total").html('' + grand_total.toFixed(2));
    $("#grand_total_val").val(grand_total.toFixed(2));
    //round up value for the textbox
    round_off = parseFloat(round_off);
    var r = round_off.toFixed(2);
    $("#round_off").val(r);

}
/**
 * for default delivery location for Purchase Advice
 */
function get_default_location() {
    var supplier_id = $("#supplier_select").val();
    var default_location;
    $("#supplier_select").find("option").each(function (i, opt) {
        if (opt.value === supplier_id) {
            default_location = $(opt).attr('default_location');
            $("#customer_default_location").val(default_location);
        }
    });
    default_delivery_location();
}

/**
 * product_autocomplete for purchase
 */
function product_autocomplete_purchase(id) {

    $("#add_purchase_product_name_" + id).autocomplete({
        position: {
            my: "left bottom",
            at: "left top"
        },
        select: function (event, ui) {
            var term = $("#add_purchase_product_name_" + id).val();
            $.ajax({
                url: baseurl + '/fetch_products',
                data: {"term": term},
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    $("#add_product_id_" + id).val(obj.data_array[0].id);
                },
            });
        }
    });
}
//function product_autocomplete_purchase1(id) {
//
//    $("#add_purchase_product_name_" + id).autocomplete({
//        minLength: 1,
//        dataType: 'json',
//        type: 'GET',
//        autoFocus: true,
//        autoselect: 'first',
//        source: function (request, response) {
//            $("#add_purchase_product_name_" + id).addClass('loadinggif');
//            $.ajax({
//                url: baseurl + '/fetch_products',
//                data: {"term": request.term},
//                success: function (data) {
//                    var main_array = JSON.parse(data);
//                    var arr1 = main_array['data_array'];
//                    response(arr1);
//                    $("#add_purchase_product_name_" + id).removeClass('loadinggif');
//                },
//            });
//        },
//        open: function (event, ui) {
//            var $input = $(event.target);
//            var $results = $input.autocomplete("widget");
//            var scrollTop = $(window).scrollTop();
//            var top = $results.position().top;
//            var height = $results.outerHeight();
//            if (top + height > $(window).innerHeight() + scrollTop) {
//                newTop = top - height - $input.outerHeight();
//                if (newTop > scrollTop)
//                    $results.css("top", newTop + "px");
//            }
//        },
//        select: function (event, ui) {
//            $("#add_product_id_" + id).val(ui.item.id);
//        }
//    });
//
//    $(window).scroll(function (event) {
//        $('.ui-autocomplete.ui-menu').position({
//            my: 'left bottom',
//            at: 'left top',
//            of: '#tags'
//        });
//    });
//}

function create_delivery_order_PS(row_id) {
    grand_total_delivery_order();
    var qty = $('#quantity_' + row_id).val();
    $('#present_shipping_' + row_id).val(qty);
    change_quantity(row_id);
}

/**
 * Comment: validation for digit, skip alphabets and symbols except dot
 * 
 */
function validation_digit(evt) {
    var event = (evt.which) ? evt.which : event.keyCode
    return ((event >= 48 && event <= 57) || event == 46 || event == 8 || event == 46
 || event == 37 || event == 39);
}

function validation_only_digit(evt) {
    var event = (evt.which) ? evt.which : event.keyCode
    return ((event >= 48 && event <= 57));
}
$(document).ready(function () {
    $('body').on('keypress','.focus_on_enter',function(e){
     
        if (e.which == 13) {
            e.preventDefault();
            var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
            if (!$next.length) {
                // $next = $('[tabIndex=1]');
            }
            var name = $next.focus();
        }
    })
    $('.focus_on_enter').on('keypress', function (e) {
        if (e.which == 13) {
            e.preventDefault();
            var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
            if (!$next.length) {
                // $next = $('[tabIndex=1]');
            }
            var name = $next.focus();
        }
    });
});

function numbersOnly(Sender,evt,isFloat,isNegative) {
            if(Sender.readOnly) return false;       

            var key   = evt.which || !window.event ? evt.which : event.keyCode;
            var value = Sender.value;

            if((key == 46 || key == 44) && isFloat){                
                var selected = document.selection ? document.selection.createRange().text : "";
                if(selected.length == 0 && value.indexOf(".") == -1 && value.length > 0) Sender.value += ".";
                return false;
            }
            if(key == 45) { // minus sign '-'
                if(!isNegative) return false;
                if(value.indexOf('-')== -1) Sender.value = '-'+value; else Sender.value = value.substring(1);
                if(Sender.onchange != null) {
                    if(Sender.fireEvent){
                        Sender.fireEvent('onchange');
                    } else {
                        var e = document.createEvent('HTMLEvents');
                            e.initEvent('change', false, false);
                        Sender.dispatchEvent(e);
                    }
                }

                var begin = Sender.value.indexOf('-') > -1 ? 1 : 0;
                if(Sender.setSelectionRange){
                    Sender.setSelectionRange(begin,Sender.value.length);
                } else {
                    var range = Sender.createTextRange();
                    range.moveStart('character',begin);
                    range.select();                 
                }

                return false;
            }
            if(key > 31 && (key < 48 || key > 57)) return false;
        }
        
    function onlyPercentage(evt) {
            var val1;
            evt = evt || window.event;
            sVal = (evt.srcElement || evt.target).value;
            var evt   = evt.which || !window.event ? evt.which : event.keyCode;
            
            if (!(evt == 46 || evt == 8 ||(evt >= 48 && evt <= 57)))
                return false;
            var parts = sVal.split('.');            
            if (parts.length > 2)
                return false;
            if (evt == 46)
                return (parts.length == 1);
            if (evt != 46) {
                var currVal = String.fromCharCode(evt);
                val1 = parseFloat(String(parts[0]) + String(currVal));
                if(parts.length==2)
                    val1 = parseFloat(String(parts[0])+ "." + String(currVal));
            }
 
            if (val1 > 99.99)
                return false;
            if (parts.length == 2 && parts[1].length >= 2) return false;
        }    