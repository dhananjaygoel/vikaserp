var baseurl = $('#baseurl').attr('name');
$(document).ready(function() {
    $("#existing_customer").click(function() {
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#new_customer").click(function() {
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    $("#optionsRadios4").click(function() {
        $(".plusvat").show();

    });
    $("#optionsRadios3").click(function() {
        $(".plusvat").hide();

    });
    $("#existing_customer_name").autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        source: function(request, response) {
            $.ajax({
                url: baseurl + '/fetch_existing_customer',
                data: {"term": request.term},
                success: function(data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    response(arr1);
                },
            });
        },
        select: function(event, ui) {
            $("#existing_customer_id").val(ui.item.id);
        }
    });

    $('#expected_delivery_date').datepicker({
        format: 'mm-dd-yyyy'
    });
    $('#datepickerDateComponent').datepicker();

    $("#add_product_row").on("click", function() {
        var current_row_count = $(".add_product_row").length + 1;
        $.ajax({
            type: "GET",
            url: baseurl + '/get_units'
        }).done(function(data) {
            var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '<option value="" selected="">Unit</option>';
            for (var key in arr1) {
                html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
            }
            $("#units_" + current_row_count).html(html);
        });
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-3">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="quantity_' + current_row_count + '" class="form-control" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '<option value="" selected="">Unit</option>' +
                '<option value="1">Kg</option>' +
                '<option value="2">cm</option>' +
                '<option value="3">metre</option>' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="price" name="product[' + current_row_count + '][price]">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-4">' +
                '<div class="form-group">' +
                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
                '</div>' +
                '</td>' +
                '</tr>';
        $("#add_product_table").children("tbody").append(html);
    });

    $("#add_inquiry_location").on("change", function() {
        if ($("#add_inquiry_location").val() == "other")
            $("#other_location_input_wrapper").show();
        else
            $("#other_location_input_wrapper").hide();
    });

});

/**
 * Comment
 */
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
    $("#add_product_name_" + id).autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        source: function(request, response) {
            $.ajax({
                url: baseurl + '/fetch_products',
                data: {"term": request.term},
                success: function(data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    response(arr1);
                },
            });
        },
        select: function(event, ui) {
            $("#product_price_" + id).val(ui.item.product_price);
            $("#add_product_id_" + id).val(ui.item.id);
        }
    });

}