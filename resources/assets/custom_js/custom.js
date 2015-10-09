$(document).ready(function() {
    $("#product_type2").click(function() {
        $(".thick").hide();
    });
    $("#product_type1").click(function() {
        $(".thick").show();
    });
    $('#product_sub_category_select').change(function() {

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
            success: function(data) {
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
$(document).ready(function() {
    $("#exist_customer").click(function() {
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#new_customer").click(function() {
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    $("#optionsRadios4").click(function() {
        $(".supplier").show();
    });
    $("#optionsRadios3").click(function() {
        $(".supplier").hide();
    });
    $("#optionsRadios6").click(function() {
        $(".plusvat").show();
    });
    $("#optionsRadios5").click(function() {
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
$(document).ready(function() {
    $("#addmore1").click(function() {
        $(".row5").hide();
        $(".row6").show();
        $(".row7").show();
    });
    $("#addmore2").click(function() {
        $(".row7").hide();
        $(".row8").show();
        $(".row9").show();
    });
    $("#addmore3").click(function() {
        $(".row9").hide();
        $(".row10").show();
        $(".row11").show();
    });
    $("#addmore4").click(function() {
        $(".row11").hide();
        $(".row12").show();
    });
    $('#add_more_product').on("click", function() {
        var current_row_count = $(".add_product_row").length + 1;
        $.ajax({
            type: "GET",
            url: baseurl + '/get_units'
        }).done(function(data) {
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
$(function() {
    $('.smstooltip').tooltip();
});
$(function() {
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
    }).done(function(data) {
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
    }).done(function(data) {
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
    }).done(function(data) {
        $('.alert-success1').show();
    });
}


$('#onenter_prevent input').keypress(function(e) {
    if (e.which == 13) {
        return false;
    }
});

$("#product_size").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function(request, response) {
        $("#product_size").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_product_size',
            data: {"term": request.term},
            success: function(data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#product_size").removeClass('loadinggif');
            },
        });
    },
    select: function(event, ui) {
        $("#product_size").val(ui.item.id);
    }

});
$("#order_size").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function(request, response) {
        $("#order_size").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_product_size',
            data: {"term": request.term},
            success: function(data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#order_size").removeClass('loadinggif');
            },
        });
    },
    select: function(event, ui) {
        $("#order_size").val(ui.item.id);
    }

});

$("#search_text").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function(request, response) {
        $("#search_text").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_product_name',
            data: {"term": request.term},
            success: function(data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#search_text").removeClass('loadinggif');
            },
        });
    },
    select: function(event, ui) {
        $("#search_text").val(ui.item.id);
    }
});

$('#save_all_price_btn').click(function() {

    $.ajax({
        type: 'post', url: baseurl + '/update_all_price',
        data: $('#save_all_price').serialize(),
        success: function(data) {
            $('html,body').animate({scrollTop: 0}, 'slow');
            $('.alert-success1').show();
        }
    });
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

$('#labour').on('keyup', function(e) {
    if (e.which === 46)
        return false;
}).on('input', function() {
    var self = this;
    setTimeout(function() {
        if (self.value.indexOf('.') != -1)
            self.value = parseInt(self.value, 10);
    }, 0);
});
$('#save_all_size_btn').click(function() {
    var token = $('#_token').val();
    $.ajax({
        type: 'POST',
        url: baseurl + '/update_all_sizes',
        data: {form_data: $('#save_all_product_sizes').serialize(), _token: token},
        success: function(data) {
            $('.alert-success1').show();
            $('html, body').animate({
                scrollTop: $('.navbar-brand').offset().top
            }, 1000);

        }
    });
});
$('body').delegate(".pendingpadvice", "click", function() {
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

$('body').delegate("#redirect_url_for_sorting", "click", function() {
    var href = $('#redirect_url_for_sorting').attr("href");
    window.parent.location.href = href;
});

$('body').delegate(".each_product_detail", "blur", function() {
    var current_product = $(this).val()
    var cur_product_id = $(this).attr("data-productid");

    if (current_product == "") {
        $(this).css('border-color', '#e7ebee');

        $('#add_product_id_' + cur_product_id).val('');
        $('#add_product_id_' + cur_product_id).attr('data-curname', '');
    } else {

        var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
        if (related_cur_product_id == "") {
            $(this).focus();
            $(this).css('border-color', 'red');
            $(this).css('box-shadow', 'none');
        } else {
            $(this).css('border-color', '#e7ebee');
        }
    }
});
$('body').delegate(".pendingorder", "click", function() {
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
        success: function(data) {
//            $('.custom_alert_success').fadeOut(5000);
            $('.alert-success1').show();
            $('html, body').animate({
                scrollTop: $('.navbar-brand').offset().top
            }, 1000);

        }
    });
}