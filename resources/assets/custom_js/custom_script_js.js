$(document).ready(function () {
    $("#optionsRadios1").click(function () {
        $(".supplier").hide();
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#optionsRadios2").click(function () {

        $(".exist_field").show();
        $(".customer_select").hide();
        $(".supplier").hide();
    });

    $("#optionsRadios3").click(function () {
        $(".supplier").hide();

    });
    $("#optionsRadios4").click(function () {
        $(".supplier").show();

    });
    $("#optionsRadios6").click(function () {
        $(".plusvat").show();

    });
    $("#optionsRadios5").click(function () {
        $(".plusvat").hide();

    });
    $('#add_order_location').change(function () {
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
    
    
    $("#add_product_row_delivery_challan").on("click", function() {
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
                '<td class="col-md-2">'+
                '<div class="form-group">'+
                '<input id="actual_pieces_' + current_row_count + '" class="form-control" placeholder="Actual Pieces" name="product[' + current_row_count + '][actual_pieces]" value="" type="text">'+
                '</div>'+
                '</td>'+
                '<td class="col-md-2">'+
                '<div class="form-group">'+
                '<input id="present_shipping_' + current_row_count + '" class="form-control text-center" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">'+
                '</div>'+
                '</td>'+
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
    
    $("#add_product_row_delivery_order").on("click", function() {
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
                '<div class="form-group ">' +
                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '<option value="" selected="">Unit</option>' +
                '</select>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">'+
                '<div class="form-group">'+
                '<input id="present_shipping_' + current_row_count + '" class="form-control text-center" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">'+
                '</div>'+
                '</td>'+
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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
    
});
function grand_total_delivery_order() {
    /**
     * Comment
     */
    var current_row_count = $(".add_product_row").length;
    var total_price = 0;
    for (var i = 0; i<= current_row_count; i++){  
        if(parseInt($('#product_price_'+i).val())){
            total_price= total_price+parseInt($('#product_price_'+i).val());
        }
    }
    var vat_val = parseInt(parseInt(total_price) * parseInt($('#vat_percentage').val()))/100;
    var grand_total = parseInt(total_price) + parseInt(vat_val);
    $('#grand_total').val(grand_total);   
}

