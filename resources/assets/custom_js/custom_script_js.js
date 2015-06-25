$(document).ready(function () {
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
        if ($('#add_order_location').val() == '-2') {
            $('.locationtext').toggle();
        }

    });
    $('#add_inquiry_location').change(function () {
        if ($('#add_order_location').val() == '-2') {
            $('.locationtext').toggle();
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

    $('#expected_delivery_date_order').datepicker({
        format: 'dd-mm-yyyy'
    });
    $('#estimated_delivery_date').datepicker({
        format: 'dd-mm-yyyy'
    });
    $('#sales_daybook_date').datepicker({
        format: 'dd-mm-yyyy'
    });


    $("#add_product_row_delivery_challan").on("click", function () {
        var current_row_count = $(".add_product_row").length + 1;
        $.ajax({
            type: "GET",
            url: baseurl + '/get_units'
        }).done(function (data) {
            var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '<option value="" selected="">Unit</option>';
            for (var key in arr1) {
                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
            }
            $("#units_" + current_row_count).html(html);
        });
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-2">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="quantity_' + current_row_count + '" class="form-control" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text" onfocus="grand_total_delivery_order();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input id="actual_pieces_' + current_row_count + '" class="form-control" placeholder="Actual Pieces" name="product[' + current_row_count + '][actual_pieces]" value="" type="text">' +
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
                '<option value="" selected="">Unit</option>' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input id="amount_' + current_row_count + '" class="form-control" placeholder="amount" name="product[' + current_row_count + '][amount]" value="" type="text" readonly="readonly">' +
                '</div>' +
                '</td>' +
                '</tr>';
        $("#add_product_table_delivery_challan").children("tbody").append(html);
    });

    $("#add_product_row_delivery_order").on("click", function () {
        var current_row_count = $(".add_product_row").length + 1;
        $.ajax({
            type: "GET",
            url: baseurl + '/get_units'
        }).done(function (data) {
            var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '<option value="" selected="">Unit</option>';
            for (var key in arr1) {
                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
            }
            $("#units_" + current_row_count).html(html);
        });
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-2">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="quantity_' + current_row_count + '" class="form-control" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="grand_total_delivery_order();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '<option value="" selected="">Unit</option>' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="present_shipping_' + current_row_count + '" class="form-control" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text" onblur="change_quantity(' + current_row_count + ');">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]" onblur="grand_total_delivery_order();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="pending_qunatity_value_' + current_row_count + '" class="form-control text-center" name="product[' + current_row_count + '][pending_quantity]" value="" type="hidden">' +
                '<div id="pending_qunatity_' + current_row_count + '"></div>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '</tr>';
        $("#add_product_table_delivery_order").children("tbody").append(html);
    });


    challan_ids = [];
    grand_total_delivery_order();
    if($("#add_product_table_delivery_challan").length>0){
        fetch_price();
    }
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

/**
 * Comment
 */
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
    var tot_quty = $("#qunatity_" + key).val();
    ;
    if (parseInt(present_shipping) > parseInt(quantity)) {
        alert("Present Shipping should not greater than quantity.");
//        var shipping_error = '<div id="flash_error_present_shipping" class="alert alert-warning no_data_msg_container"> Present Shipping should not greater than quantity.</div>';
//        $("#flash_error_present_shipping").html(shipping_error);
        $("#present_shipping_" + key).val(0);
        $("#pending_qunatity_" + key).html("<span>" + quantity + "</span");
    } else {

        $("#flash_error_present_shipping").html('');
        var rem_quantity = parseInt(quantity) - parseInt(present_shipping);
//    alert(quantity +" "+ present_shipping);
        $("#pending_qunatity_" + key).html("<span class='text-center'>" + rem_quantity + "</span");
    }
    grand_total_delivery_order();
    
}


/**
 * Change Amount
 */
function name(key) {
    var price = $("#product_price_" + key).val();
    var quantity = $("#quantity_" + key).val();
    alert('price' + price + " qty " + quantity);
}
//function grand_total_delivery_order(key) {
/**
 * Comment
 */

function grand_total_delivery_order() {
//    var quantity = $("#quantity_"+key).val();
//    alert('quantity '+quantity);
//    $("#pending_qunatity_value_"+key).val(quantity);
//    $("#pending_qunatity_"+key).html('<span class="text-center">'+quantity+'</span>');

    var current_row_count = $(".add_product_row").length;
//    alert(current_row_count);
    var total_price = 0;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseInt($('#product_price_' + i).val())) {
            var present_shipping = parseInt($("#present_shipping_" + i).val());
            total_price = total_price + (parseInt($('#product_price_' + i).val()) * present_shipping);
        }
    }
//    alert(total_price);
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
//            alert(discount_value );
            grand_total = grand_total + discount_value;
        }

    }
    if ($("#freight_value").length > 0) {
        if ($("#freight_value").val() > 0) {
            var freight_value = parseInt($("#freight_value").val());
            grand_total = grand_total + freight_value;
        }

    }
    if ($("#loading_charge").length > 0) {
        if ($("#loading_charge").val() > 0) {
            var loading_charge = parseInt($("#loading_charge").val());
            grand_total = grand_total + loading_charge;
        }

    }

//    alert(vat_val);

    $('#grand_total').val(grand_total);
}

/**
 * Fetch price of the product
 */
function fetch_price() {
//    alert('test');
var current_row_count = $(".add_product_row").length;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseInt($('#product_price_' + i).val())) {
            var actual_quantity= $("#actual_quantity_"+i).val();
            var rate = $("#product_price_"+i).val();
            var amount = parseFloat(rate)* parseInt(actual_quantity);
            $("#amount_"+i).html('<span class="text-center">'+amount+'</span>');
        }
    }
}

/**
 * Grand total for creating independent delivery order
 */
function calculate_grand_total() {
    var current_row_count = $(".add_product_row").length;
//    alert(current_row_count);
    var total_price = 0;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseInt($('#product_price_' + i).val())) {
//            
            var quantity = parseInt($("#quantity_" + i).val());
            total_price = total_price + (parseInt($('#product_price_' + i).val()) * quantity);
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

    $('#grand_total').val(grand_total);
}

/*
 * Function for grand total for challan
 */
function grand_total_challan() {

    var current_row_count = $(".add_product_row").length;
//    alert(current_row_count);
    var total_price_products = 0;
    for (var i = 0; i <= current_row_count + 1; i++) {
        if (parseInt($('#product_price_' + i).val())) {
            var unit_id = $("#units_" + i).val();
            var product_id = $("#add_product_id_" + i).val();
            var product_wt;
            var actual_quantity = parseInt($("#actual_quantity_" + i).val());
//            if ($("#unit_name_" + i).val() != 'kg' || $("#unit_name_" + i).val() != 'KG')
//            {
//                $.ajax({
//                    type: 'GET',
//                    url: baseurl + '/get_product_weight',
//                    data: {product_id: product_id}
//                }).done(function (data) {
//                    product_wt=data;
////                        alert(data);
//                });

//                var actual_pieces = $("#actual_pieces_" + i).val();
//                actual_quantity = actual_pieces;
//            }

            total_price_products = total_price_products + (parseInt($('#product_price_' + i).val()) * actual_quantity);
        }
    }
//    alert(total_price);
    var vat_val = 0;

    if ($('#optionsRadios6').is(':checked')) {
        vat_val = (parseInt(total_price_products) * $('#vat_percentage').val()) / 100;
    }
    if ($('#optionsRadios5').is(':checked')) {
        vat_val = 0;
    }
    var total_price = parseInt(total_price_products) + parseInt(vat_val);



    if ($("#discount_value").length > 0) {
        if ($("#discount_value").val() > 0) {

            var discount_value = (parseFloat($("#discount_value").val()) * total_price) / 100;
//            alert(discount_value );
            total_price = total_price + discount_value;
        }

    }
    if ($("#freight_value").length > 0) {
        if ($("#freight_value").val() > 0) {
            var freight_value = parseInt($("#freight_value").val());
            grand_total = total_price + freight_value;
        }

    }
    if ($("#loading_charge").length > 0) {
        if ($("#loading_charge").val() > 0) {
            var loading_charge = parseInt($("#loading_charge").val());
            grand_total = total_price + loading_charge;
        }

    }


//    alert(vat_val);
    $("#total_price").val(total_price);

    $('#grand_total').val(grand_total);
}
