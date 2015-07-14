var baseurl = $('#baseurl').attr('name');
var _token = $('#csrf_token').attr('content');


$(document).ready(function () {
    $("#existing_customer").click(function () {
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#new_customer").click(function () {
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    $("#optionsRadios4").click(function () {
        $(".plusvat").show();
    });
    $("#optionsRadios3").click(function () {
        $(".plusvat").hide();
    });
    $("#existing_supplier").click(function () {
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#new_supplier").click(function () {
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    $("#exclusive_of_vat").click(function () {
        $(".plusvat").show();

    });
    $("#inclusive_of_vat").click(function () {
        $(".plusvat").hide();

    });

    $("#existing_customer_name").autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        source: function (request, response) {
            $.ajax({
                url: baseurl + '/fetch_existing_customer',
                data: {"term": request.term},
                success: function (data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    response(arr1);
                },
            });
        },
        select: function (event, ui) {
            $("#existing_customer_id").val(ui.item.id);
//            alert(ui.item.id);
            $("#customer_default_location").val(ui.item.delivery_location_id);
            default_delivery_location();
        }
    });
    $("#existing_supplier_name").autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        source: function (request, response) {
            $.ajax({
                url: baseurl + '/fetch_existing_customer',
                data: {"term": request.term},
                success: function (data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    response(arr1);
                },
            });
        },
        select: function (event, ui) {
            $("#existing_supplier_id").val(ui.item.id);
            $("#customer_default_location").val(ui.item.delivery_location_id);
            default_delivery_location();
        }
    });
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    $('#expected_delivery_date').datepicker({
        format: 'mm-dd-yyyy',
        startDate: today,
        autoclose: true
    });
    $('#datepickerDateComponent').datepicker();
    $("#add_product_row").on("click", function () {
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
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-3">' +
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
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-4">' +
                '<div class="form-group">' +
                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
                '</tr>';
        $("#add_product_table").children("tbody").append(html);
        var purchase_html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-3">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
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
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-4">' +
                '<div class="form-group">' +
                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '</tr>';
        $("#add_product_table_purchase").children("tbody").append(purchase_html);
    });

    $("#add_purchase_advice_product_row").on("click", function () {
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
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-3">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '<input type="hidden" name="product[' + current_row_count + '][purchase]" value="">' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<select class="form-control units_dropdown" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="actual_pieces" id="actual_pieces' + current_row_count + '" name="product[' + current_row_count + '][actual_pieces]">' +
                '</div>' +
                '</td>' +
                '<td>' +
                '<div class="form-group" >' +
                '<input id="quantity_' + current_row_count + '" class="form-control" placeholder="" name="product[' + current_row_count + '][quantity]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group" style="width:100%;">' +
                '<input type="text" class="form-control pshipping" placeholder="Present Shipping" id="present_shipping' + current_row_count + '" name="product[' + current_row_count + '][present_shipping]">' +
                '</div><div class="clearfix"></div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control units_dropdown" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '</tr>';
        $("#create_purchase_advise_table").children("tbody").append(html);
    });

    $("#add_editadvice_product_row").on("click", function () {
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
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-3">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="quantity_' + current_row_count + '" class="form-control" placeholder="Present shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
                '</div>' +
                '</td><td></td>' +
                '<td class="col-md-4">' +
                '<div class="form-group">' +
                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '</tr>';
        $("#add_product_table").children("tbody").append(html);
    });

    $("#add_inquiry_location").on("change", function () {
        if ($("#add_inquiry_location").val() == "other")
            $("#other_location_input_wrapper").show();
        else
            $("#other_location_input_wrapper").hide();
    });
//
//    $('#loc1').change(function() {
//        if ($('#loc1').val() == '3') {
//            $('.locationtext').toggle();
//        }
//
//    });
});

function save_price_inquiry_view(id, inq_id) {

    var id = $("#hidden_inquiry_product_id_" + id).val();
    var updated_price = $("#difference_" + id).val();
    if (updated_price == "") {
        event.preventDefault();

    } else {
        $.ajax({
            type: 'POST',
            url: baseurl + '/store_price',
            data: {id: id, _token: _token, updated_price: updated_price}
        }).done(function () {
//            location.reload();
//            $("#difference_" + id).val(updated_price);
            var html_svbtn = '<span type="button" class="btn btn-default" >Save</span>';
            $("#save_btn_"+id).html(html_svbtn);
            $("#save_price_inquiry_view_" + id).removeClass('btn-primary');
            var price_val = '' + updated_price;
            $("#price_" + id).html(price_val);
            $("#save_price_inquiry_view_" + id).addClass('btn-default');
            var html_btn = '<span type="button" class="btn btn-default" >Save</span>';
            $("#product_save_btn_"+id).html(html_btn);
            $('#inquire_msg').css('display', 'block');
           var length_btns = $(".customerview_table .btn-primary").length;
//            alert("lengths "+length_btns);
            if ( length_btns > 0) {
//                alert('button found');
                var html = '<span title="You can not click unless you save all prices" type="button" class="btn btn-default smstooltip" >Send SMS</span>';
                $("#send_sms_button").html(html);
            }
            else {
                var url_send_sms = baseurl+'/inquiry/'+inq_id+'?sendsms=true';
            var html = '<a href="'+url_send_sms+'" title="SMS would be sent to Party and Relationship Manager" type="button" class="btn btn-primary smstooltip" >Send SMS</a>';
            $("#send_sms_button").html(html);
            }

        });
    }
    $('#inquire_msg').css('display', 'block');
}

function show_hide_customer(status) {
    if (status == "Pending") {
        $(".exist_field").show();
        $(".customer_select").hide();
    }
    else {
        if (status == 'Permanent') {
            $(".exist_field").hide();
            $(".customer_select").show();
        }
    }
}

/**
 * product_autocomplete
 */
function product_autocomplete(id) {

    var customer_id = $('#existing_customer_id').val();

    if (customer_id == "") {
        customer_id = 0;
    }

    var delivery_location = $('#add_order_location').val();
    var location = 0;
    var location_difference = 0;

    if (delivery_location > 0) {

        location = $('#add_order_location').val();

    } else if (delivery_location == 'other') {

        location_difference = $('#location_difference').val();
        location = 0;
    }

    $("#add_product_name_" + id).autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        source: function (request, response) {

            $("#add_product_name_" + id).addClass('loadinggif');
            $.ajax({
                url: baseurl + '/fetch_products',
                data: {"term": request.term, 'customer_id': customer_id, 'delivery_location': location, 'location_difference': location_difference},
                success: function (data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    response(arr1);
                },
            });
        },
        select: function (event, ui) {
//            alert(ui.item.id);
//            alert(ui.item.product_price);
            $("#product_price_" + id).val(ui.item.product_price);
            $("#add_product_id_" + id).val(ui.item.id);
//            var next_row = $("#product_price_" + id).parent().parent().parent().next().attr("id");
//            $("#" + next_row).children().each(function() {
//                alert($(this).attr("class"));
//            });
            $("#add_product_name_" + id).removeClass('loadinggif');
        }
    });
}

/**
 * Comment
 */
function fetch_city() {
    var state_id = $("#select_state").val();
    $.ajax({
        type: 'GET',
        url: baseurl + '/get_cities',
        data: {state_id: state_id}
    }).done(function (data) {
        var main_array = JSON.parse(data);
        var arr1 = main_array['cities'];
        var html = '';
        for (var key in arr1) {
            html += '<option value="' + arr1[key].id + '">' + arr1[key].city_name + '</option>';
        }
        $("#select_city").html(html);
    });
}
