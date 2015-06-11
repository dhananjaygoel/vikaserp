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
        source: baseurl + '/fetch_existing_customer',
        minLength: 2,
        dataType: 'json',
        type: 'GET',
//        _renderItem: function(ul, data_array) {
//            return $("<li>")
//                    .attr("data-value", data_array.value)
//                    .append(data_array.label)
//                    .appendTo(ul);
//        },
        source: function(request, response) {
            $.ajax({
                url: baseurl + '/fetch_existing_customer',
                data: {"term": request.term},
                success: function(data) {
                    response(data.split(" "));
                }
//                success: function(data) {
//                    response($.map(data, function(item) {
//                        return {
//                            label: item.label,
//                            val: item.value
//                        }
//                    }))
//                },
            });
        }
    });

    $('#expected_delivery_date').datepicker({
        format: 'mm-dd-yyyy'
    });
    $('#datepickerDateComponent').datepicker();

    $("#add_product_row").on("click", function() {
        var current_row_count = $(".add_product_row").length + 1;
        var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
                '<td class="col-md-3">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '">' +
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
                '<input type="text" class="form-control" value="price" id="price" name="product[' + current_row_count + '][price]">' +
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