
$(document).ready(function () {
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
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
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
            '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
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
}

/**
 * calutate_pending_order
 */
function calutate_pending_order(qty, key) {

    var shipping = $('#present_shipping_' + key).val();
    var pending = $('#pending_order_org' + key).val();
//    if (parseInt(shipping) <= parseInt(pending)) {
    if (parseInt(shipping) <= parseInt(qty)) {
        $('#pending_order_' + key).val(qty - shipping);
    } else {
        alert('Present shipping should not be greater than pending order');
        $('#present_shipping_' + key).val("");
    }
}

/**
 * Comment
 */
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

//$('#expected_delivery_date').datepicker().on('changeDate', function (e) {
//    $('#expected_delivery_date').datepicker('hide');
//});
/**
 * Comment
 */
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
        $("#order_size").val(ui.item.id);
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
            $('.alert-success1').show();
        }
    });
});
/**
 * Comment
 */
function this_price(a, b) {
//    var str = $("#set_" + a + b).val();

//    alert(str.indexOf('.'))

//    if (str.indexOf('.') == -1) {
//        $("#set_" + a + b).val(str.substr(0, 3));
//    }
//
//    if (str.indexOf('.') > -1) {
//        alert('hi')
//        var num = parseFloat($("#set_" + a + b).val());
//        $("#set_" + a + b).val(num.toFixed(2));
//    }


//    var num = parseFloat($("#set_" + a + b).val());
//    if (str == "" || isNaN(str) == true) {
//        $("#set_" + a + b).val("");
//    } else {
//        if (Number.isNaN(num) && num.toFixed(2).toString() == str) {
//        } else {
//            $("#set_" + a + b).val(num.toFixed(2));
//        }
//    }


//
//    var reg = "\d{0,3}[.]\d+|^\d{0,3}$";
//    if ($(this).val().match(reg) == null)
//        alert('Invalid input!');

}

/**
 * Comment
 */
function test(a, b, e) {
    var abc = $(e).val();
//    $("#valueSconto").on("input", function () {
        $(e).val(function (i, abc) {
            return abc.replace(/\d{3}|[^\d{2}\.]|^\./g, "");
        });
//    });
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