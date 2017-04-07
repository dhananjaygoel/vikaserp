<!-- form-autocomplete maintained by sukohi -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
            $(document).ready(function(){
            {!! FormAutocomplete::selector('#existing_customer_name')->source(function(){
    return \App\Customer::where('customer_status', '=', 'permanent')
                    ->orderBy('tally_name', 'ASC')
                    ->lists('tally_name');  // You need to return array values.
    }) !!}
            {!! FormAutocomplete::selector('#existing_supplier_name')->source(function(){
    return \App\Customer::where('customer_status', '=', 'permanent')
                    ->orderBy('tally_name', 'ASC')
                    ->lists('tally_name');  // You need to return array values.
    }) !!}
//  --------------------------------------Enter product name-------------------------------------------------------------             
                {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
//  --------------------------------------Dynamic delivery order product name---------------------------------------------           
    $("#add_product_row_delivery_order").on("click", function () {
    var current_row_count = $(".add_product_row").length + 2;
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
            var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-2">' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][product_category_id]" id="add_product_id_' + current_row_count + '">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control dileep" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="text" onblur="create_delivery_order_PS(' + current_row_count + ');">' +
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
            '<input id="present_shipping_' + current_row_count + '" class="form-control" placeholder="Present Shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text" onblur="change_quantity(' + current_row_count + ');">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-2">' +
            '<div class="form-group">' +
            '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]" onblur="grand_total_delivery_order();">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group inquiry_vat_chkbox">' +
            '<input type="checkbox" class="vat_chkbox" name="product[' + current_row_count + '][vat_percentage]" value="yes">' +
            '<input id="pending_qunatity_value_' + current_row_count + '" class="form-control text-center" name="product[' + current_row_count + '][pending_quantity]" value="" type="hidden">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<div id = "pending_qunatity_' + current_row_count + '"></div>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-2">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
            '</tr>';
            $("#add_product_table_delivery_order").children("tbody").append(html);
//  --------------------------------------Enter product name---------------------------------------------              
                {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });
//  --------------------------------------Dynamic inquiry product name--------------------------------------------- 
            $("#add_product_row").on("click", function() {
    var current_row_count = $(".add_product_row").length + 1;
    var row_id = $(".add_product_row").length;
    $('#quantity_' + row_id).focus();
            $.ajax({
            type: "GET",
                    url: baseurl + '/get_units'
            }).done(function(data) {
               $('#quantity_' + row_id).focus();
    var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
    html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
    }
    $("#units_" + current_row_count).html(html);
    });
            var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" data-productid="' + current_row_count + '" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '" value="">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group inquiry_vat_chkbox">' +
            '<input type="checkbox" class="vat_chkbox" name="product[' + current_row_count + '][vat_percentage]" value="yes">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-3">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
            '</tr>';
            $("#add_product_table").children("tbody").append(html);
            var purchase_html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-4">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '</tr>';
            $("#add_product_table_purchase").children("tbody").append(purchase_html);
//  --------------------------------------Enter product name---------------------------------------------              
                {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });
//  --------------------------------------Dynamic add product delivery challan name---------------------------------------------     
            $("#add_product_row_delivery_challan").on("click", function () {
    var current_row_count = $(".add_product_row").length + 1;
     var row_id = $(".add_product_row").length;
        $.ajax({
            type: "GET",
                    url: baseurl + '/get_units'
            }).done(function (data) {
                $('#quantity_' + row_id).focus();
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
                '<input class="form-control each_product_detail" data-productid="'+ current_row_count +'" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
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
//  --------------------------------------Enter product name---------------------------------------------              
                {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });
    
            $("#add_purchase_advise_product_row").on("click", function() {
    var current_row_count = $(".add_product_row").length + 1;
    var row_id = $(".add_product_row").length;
     
            $.ajax({
            type: "GET",
                    url: baseurl + '/get_units'
            }).done(function(data) {
                 $('#quantity_' + row_id).focus();
    var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
    html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
    }
    $("#units_" + current_row_count).html(html);
    });
            var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
                '<input class="form-control each_product_detail" data-productid="'+ current_row_count +'" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="purchase_order_advise_product_autocomplete(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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
            var purchase_html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-4">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '</tr>';
            $("#add_product_table_purchase").children("tbody").append(purchase_html);
//  --------------------------------------Enter product name---------------------------------------------              
                {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });
    
    
            $("#add_purchase_advice_product_row").on("click", function() {
    var current_row_count = $(".add_product_row").length + 1;
    var row_id = $(".add_product_row").length;
      
            $.ajax({
            type: "GET",
                    url: baseurl + '/get_units'
            }).done(function(data) {
                $('#quantity_' + row_id).focus();
    var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
    html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
    }
    $("#units_" + current_row_count).html(html);
    });
            var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
                '<input class="form-control each_product_detail" data-productid="'+ current_row_count +'" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="purchase_order_advise_product_autocomplete(' + current_row_count + ');">' +
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
            '<input id="quantity_' + current_row_count + '" readonly="" class="form-control each_product_qty" placeholder="" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-2">' +
            '<div class="form-group" style="width:100%;">' +
            '<input type="text" class="form-control pshipping" placeholder="Present Shipping" id="present_shipping' + current_row_count + '" name="product[' + current_row_count + '][present_shipping]">' +
            '</div><div class="clearfix"></div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input type="tel" class="form-control units_dropdown" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-2">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '</tr>';
            $("#create_purchase_advise_table").children("tbody").append(html);
        {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });

            $("#add_editadvice_product_row").on("click", function() {
    var current_row_count = $(".add_product_row").length + 1;
    var row_id = $(".add_product_row").length;
     
            $.ajax({
            type: "GET",
                    url: baseurl + '/get_units'
            }).done(function(data) {
                 $('#quantity_' + row_id).focus();
    var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
    html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
    }
    $("#units_" + current_row_count).html(html);
    });
            var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
                '<input class="form-control each_product_detail" data-productid="'+ current_row_count +'" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
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
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Present shipping" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][present_shipping]" value="" type="tel">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-2">' +
            '<div class="form-group">' +
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td><td></td>' +
            '<td class="col-md-4">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '</tr>';
            $("#add_product_table").children("tbody").append(html);
        {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });
    });
    
    
    
    //  --------------------------------------Dynamic purchase order product name--------------------------------------------- 
            $("#add_purchase_product_row").on("click", function() {
    var current_row_count = $(".add_product_row").length + 1;
    var row_id = $(".add_product_row").length;
    $('#quantity_' + row_id).focus();
            $.ajax({
            type: "GET",
                    url: baseurl + '/get_units'
            }).done(function(data) {
               $('#quantity_' + row_id).focus();
    var main_array = JSON.parse(data);
            var arr1 = main_array['units'];
            var html = '';
            for (var key in arr1) {
    html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
    }
    $("#units_" + current_row_count).html(html);
    });
            var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" data-productid="' + current_row_count + '" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '" value="">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td>' +
           
            '<td class="col-md-3">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
            '</tr>';
            $("#add_product_table").children("tbody").append(html);
            var purchase_html = '<tr id="add_row_' + current_row_count + '" class="add_product_row" data-row-id="' + current_row_count + '">' +
            '<td class="col-md-3">' +
            '<div class="form-group searchproduct">' +
            '<input class="form-control each_product_detail" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
            '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
            '<i class="fa fa-search search-icon"></i>' +
            '</div>' +
            '</td>' +
            '<td class="col-md-1">' +
            '<div class="form-group">' +
            '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
            '<input type="tel" class="form-control" placeholder="price" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
            '</div>' +
            '</td>' +
            '<td class="col-md-4">' +
            '<div class="form-group">' +
            '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
            '</div>' +
            '</td>' +
            '</tr>';
            $("#add_product_table_purchase").children("tbody").append(purchase_html);
//  --------------------------------------Enter product name---------------------------------------------              
                {!! FormAutocomplete::selector('.each_product_detail')->source(function(){
                    return \App\ProductSubCategory::with('product_category')->lists('alias_name');  // You need to return array values.
    }) !!}
    });
    
    
</script>
