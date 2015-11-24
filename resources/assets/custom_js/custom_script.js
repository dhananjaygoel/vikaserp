var baseurl = $('#baseurl').attr('name');
var _token = $('#csrf_token').attr('content');
var cache_customer = {};
var cache_product={};
var cache_supplier={}; 

/*
 * Setting new Cookie
 * @param {type} cname
 * @param {type} cvalue
 * @param {type} exdays
 * @returns {undefined}
 */
function setCookie(cname,cvalue,exdays){
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
} 

/*
 * Get the cookie value by name
 * @param {type} cname name of cookie
 * @returns {String}
 */
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

/*
 * Delete the cookie by name
 * @param {type} cname
 * @returns {undefined}
 */
function deleteCookie(cname)
{
    document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}

  
$(document).ready(function() {

    var current_time = moment().format("h:mm a");

    $(".current_time").text(current_time);

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

    $("#existing_supplier").click(function() {
        $(".exist_field").hide();
        $(".customer_select").show();
    });

    $("#new_supplier").click(function() {
        $(".exist_field").show();
        $(".customer_select").hide();
    });

    $("#exclusive_of_vat").click(function() {
        $(".plusvat").show();
    });

    $("#inclusive_of_vat").click(function() {
        $(".plusvat").hide();
    });

/*
 * autocomplete 
 */
    $("#existing_customer_name").autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        open: function(event) {
            $('.ui-autocomplete').css('height', 'auto');
            var $input = $(event.target),
                    inputTop = $input.offset().top,
                    inputHeight = $input.height(),
                    autocompleteHeight = $('.ui-autocomplete').height(),
                    windowHeight = $(window).height();
            if ((inputHeight + inputTop + autocompleteHeight) > windowHeight) {
                $('.ui-autocomplete').css('height', (windowHeight - inputHeight - inputTop - 20) + 'px');
            }
        },
        source: function(request, response) {
            $("#existing_customer_name").addClass('loadinggif');
            var customer = request.term;
                    if ( customer in cache_customer ) {
                      response( cache_customer[ customer ] );
                      $("#existing_customer_name").removeClass('loadinggif');
                      return;
                    }
                    else{
                        $.ajax({
                        url: baseurl + '/fetch_existing_customer',
                        data: {"term": request.term},
                        cache: true,
                        success: function(data) {
                            var main_array = JSON.parse(data);
                            cache_customer[ customer ] = main_array['data_array'];
                            response(main_array['data_array']);
                            $("#existing_customer_name").removeClass('loadinggif');
//                             var data_cache=JSON.parse(cache);
//                            setCookie('cache',data_cache,1);
                        },
                       });
                    }
                   
        },
        select: function(event, ui) {
            $("#existing_customer_id").val(ui.item.id);
            $("#customer_default_location").val(ui.item.delivery_location_id);
            $("#location_difference").val(ui.item.location_difference);
            default_delivery_location();
        }

    });
   
    
    $("#existing_supplier_name").autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        source: function(request, response) {
            $("#existing_supplier_name").addClass('loadinggif');
            var supplier = request.term; 
                    if ( supplier in cache_supplier ) { 
                      response( cache_supplier[ supplier ] );
                      $("#existing_supplier_name").removeClass('loadinggif');
                      return;
                    }
                    else{
                        $.ajax({
                            url: baseurl + '/fetch_existing_customer',
                            data: {"term": request.term},
                            cache: true,
                            success: function(data) {
                                var main_array = JSON.parse(data);
                                cache_supplier[ supplier ] = main_array['data_array']; 
                                response(main_array['data_array']); 
                                $("#existing_supplier_name").removeClass('loadinggif');
                            },
                        });
                    }
                    
        },
        select: function(event, ui) {
            $("#existing_supplier_id").val(ui.item.id);
            $("#customer_default_location").val(ui.item.delivery_location_id);
            default_delivery_location();
        }
    });

    $('#expected_delivery_date').datepicker({
        startDate: new Date(),
        autoclose: true
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
                '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
                '<input type="tel" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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
                '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
                '<input type="tel" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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

    $("#add_purchase_advise_product_row").on("click", function() {
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
                '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
                '<input type="tel" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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
                '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Qnty" name="product[' + current_row_count + '][quantity]" value="" type="tel" onfocus="grand_total_delivery_order();">' +
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
                '<input type="tel" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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

    $("#add_purchase_advice_product_row").on("click", function() {
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
                '<input id="quantity_' + current_row_count + '" readonly="" class="form-control each_product_qty" placeholder="" name="product[' + current_row_count + '][quantity]" value="" type="tel">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group" style="width:100%;">' +
                '<input type="text" class="form-control pshipping" placeholder="Present Shipping" id="present_shipping' + current_row_count + '" name="product[' + current_row_count + '][present_shipping]">' +
                '</div><div class="clearfix"></div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input type="tel" class="form-control units_dropdown" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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

    $("#add_editadvice_product_row").on("click", function() {
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
                '<input id="quantity_' + current_row_count + '" class="form-control each_product_qty" placeholder="Present shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="tel">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-2">' +
                '<div class="form-group">' +
                '<input type="tel" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
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

    $("#add_order_location").on("change", function() {
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            $("#add_order_location").focus();
        } else {
            $('#add_order_location').removeClass('error_validation');
            if ($("#add_order_location").val() == "other")
                $("#other_location_input_wrapper").show();
            else
                $("#other_location_input_wrapper").hide();
        }
    });
    //flash message should be hide 
        $('#flash_message').hide();
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
        }).done(function() {
            var html_svbtn = '<span type="button" class="btn btn-default normal_cursor" >Save</span>';
            $("#save_btn_" + id).html(html_svbtn);
            $("#save_price_inquiry_view_" + id).removeClass('btn-primary');
            var price_val = '' + updated_price;
            $("#price_" + id).html(price_val);
            $("#save_price_inquiry_view_" + id).addClass('btn-default');
            var html_btn = '<span type="button" class="btn btn-default" >Save</span>';
            $("#product_save_btn_" + id).html(html_btn);
            $('#inquire_msg').css('display', 'block');
            var length_btns = $(".customerview_table .btn-primary").length;
            if (length_btns > 0) {
                var html = '<span title="You can not click unless you save all prices" type="button" class="btn btn-default smstooltip normal_cursor" >Send SMS</span>';
                $("#send_sms_button").html(html);
            }
            else {
                var url_send_sms = baseurl + '/inquiry/' + inq_id + '?sendsms=true';
                var html = '<a href="' + url_send_sms + '" title="SMS would be sent to Party and Relationship Manager" type="button" class="btn btn-primary smstooltip" >Send SMS</a>';
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

/** product_autocomplete  */
function product_autocomplete(id) {
    var customer_id = $('#existing_customer_id').val();
    if (customer_id == "") {
        customer_id = 0;
    }
    var location_difference = 0;
    location_difference = $('#location_difference').val();


    $("#add_product_name_" + id).autocomplete({
        minLength: 1,
        dataType: 'json',
        type: 'GET',
        autoFocus: true,
        autoselect: 'first',
        source: function(request, response) {
            $("#add_product_name_" + id).addClass('loadinggif');
            var product = request.term;
                    if ( product in cache_product ) {
                      response( cache_product[ product ] );
                      $("#add_product_name_" + id).removeClass('loadinggif');
                      return;
                    }
                    else{
                        $.ajax({
                            url: baseurl + '/fetch_products',
                            cache: true,
                            data: {"term": request.term, 'customer_id': customer_id, 'location_difference': location_difference},
                            success: function(data) {
                                var main_array = JSON.parse(data);
                                cache_product[ product ] = main_array['data_array'];
                                response(main_array['data_array']);
                                $("#add_product_name_" + id).removeClass('loadinggif');

                            },
                        });
                    }
            
        },
        open: function(event, ui) {
            var $input = $(event.target);
            var $results = $input.autocomplete("widget");
            var scrollTop = $(window).scrollTop();
            var top = $results.position().top;
            var height = $results.outerHeight();
            if (top + height > $(window).innerHeight() + scrollTop) {
                newTop = top - height - $input.outerHeight();
                if (newTop > scrollTop)
                    $results.css("top", newTop + "px");
            }
        },
        select: function(event, ui) {
            $("#product_price_" + id).val(ui.item.product_price); // to add price in the textbox
            $("#add_product_id_" + id).val(ui.item.id);
            $("#add_product_id_" + id).attr('data-curname', ui.item.value);
        }
    });

    $(window).scroll(function(event) {
        $('.ui-autocomplete.ui-menu').position({
            my: 'left bottom',
            at: 'left top',
            of: '#tags'
        });
    });
}

/** purchase order advise product auto autocomplete */
function purchase_order_advise_product_autocomplete(id) {
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
        source: function(request, response) {

            $("#add_product_name_" + id).addClass('loadinggif');
            $.ajax({
                url: baseurl + '/fetch_products',
                data: {"term": request.term, 'customer_id': customer_id, 'delivery_location': location, 'location_difference': location_difference},
                success: function(data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    response(arr1);
                    $("#add_product_name_" + id).removeClass('loadinggif');
                },
            });
        },
        select: function(event, ui) {
            $("#add_product_id_" + id).val(ui.item.id);
        }
    });
}

$('#location_difference').on('keyup', function() {
    $(".add_product_row").each(function(index) {
        var customer_id = $('#existing_customer_id').val();
        if (customer_id == "") {
            customer_id = 0;
        }
        var location_difference = 0;
        location_difference = $('#location_difference').val();
        var rowId = $(this).attr('data-row-id');
        console.log(rowId);
        var product = $(this).find('#add_product_name_' + rowId).val();
        if (product != '') {
            var product_id = $(this).find('#add_product_id_' + rowId).val();
            $.ajax({
                url: baseurl + '/recalculate_product_price',
                data: {"product_id": product_id, 'customer_id': customer_id, 'location_difference': location_difference},
                success: function(data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    $('#product_price_' + rowId).val(arr1[0]['product_price']);
                },
            });
        }
    });
});

$('#add_order_location').on('change', function() {
    var location_difference = $('option:selected', this).attr('data-location-difference');
    $('#location_difference').val(location_difference);
    $(".add_product_row").each(function(index) {
        var customer_id = $('#existing_customer_id').val();
        if (customer_id == "") {
            customer_id = 0;
        }
        location_difference = $('#location_difference').val();
        var rowId = $(this).attr('data-row-id');
        console.log(rowId);
        var product = $(this).find('#add_product_name_' + rowId).val();
        if (product != '') {
            var product_id = $(this).find('#add_product_id_' + rowId).val();
            $.ajax({
                url: baseurl + '/recalculate_product_price',
                data: {"product_id": product_id, 'customer_id': customer_id, 'location_difference': location_difference},
                success: function(data) {
                    var main_array = JSON.parse(data);
                    var arr1 = main_array['data_array'];
                    $('#product_price_' + rowId).val(arr1[0]['product_price']);
                },
            });
        }
    });
});

/**
 * Fetch cities of particular state from state id
 */
function fetch_city() {
    var state_id = $("#select_state").val();
    $.ajax({
        type: 'GET',
        url: baseurl + '/get_cities',
        data: {state_id: state_id}
    }).done(function(data) {
        var main_array = JSON.parse(data);
        var arr1 = main_array['cities'];
        var html = '';
        for (var key in arr1) {
            html += '<option value="' + arr1[key].id + '">' + arr1[key].city_name + '</option>';
        }
        $("#select_city").html(html);
    });
}

/*
 * Seting inquiry_id to form onclick on the delete button
 * @param {type} inquiry_id
 */
function delete_inquiry_row(inquiry_id)
{
     $form = $('.delete_inquiry_form');
     
     $('.delete_inquiry_form_submit').val(inquiry_id);
}

/*Code use to delete inquiry*/
$('.delete_inquiry_form_submit').click(function() {
     
   $('#delete_inquiry').modal('hide');
    /*Form token set up*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }});
    inquiry_id=$('.delete_inquiry_form_submit').val();
    /* Mail setting form id object*/
    $form = $('.delete_inquiry_form');
    /*Mail setting form data*/
    $data = $form.serialize();
    /*Mail setting from url*/
    url =baseurl+'/inquiry/'+inquiry_id+'-delete';
  //alert(baseurl+'/inquiry/'+inquiry_id+'-delete');
   
    var posting = $.post(url, {formData: $data});
    posting.done(function(data) {
        $("#pwdr").val('');
        if(data['message']=='success')
        {
            $("#inquiry_row_"+inquiry_id).remove();
            $('#flash_message').html("Inquiry Deleted Successfully");
            $('#flash_message').removeClass('alert-danger');
            $('#flash_message').addClass('alert-success');
            $('#flash_message').fadeIn();
            $('#flash_message').fadeOut(5000);
        }
       else{
           
            $('#flash_message').html("Delete Opration Failed");
            $('#flash_message').removeClass('alert-success');
            $('#flash_message').addClass('alert-danger');
            $('#flash_message').fadeIn();
            $('#flash_message').fadeOut(5000);
       }

    }, 'json'); //done    


});

/*
 * Delete Order Form set orderId to order_id
 * @param {type} order_id
 * @returns {undefined}
 */
function delete_order_row(order_id)
{
   $('.delete_orders_modal_submit').val(order_id);
}
/*
 * Delete order by AJAX call
 */
$('.delete_orders_modal_submit').click(function() {
     $('#delete_orders_modal').modal('hide');
    /*Form token set up*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }});
    order_id=$('.delete_orders_modal_submit').val();
    /* Mail setting form id object*/
    $form = $('.delete_order_form');
    /*Mail setting form data*/
    $data = $form.serialize();
    /*Mail setting from url*/
    url =baseurl+'/order/'+order_id+'-delete';
   
   var posting = $.post(url, {formData: $data});
    posting.done(function(data) {
        $("#pwdr").val('');
        if(data['message']=='success')
        {
            $("#order_row_"+order_id).remove();
            $('#flash_message').html("Order Deleted Successfully");
            $('#flash_message').removeClass('alert-danger');
            $('#flash_message').addClass('alert-success');
            $('#flash_message').fadeIn();
            $('#flash_message').fadeOut(5000);
        }
       else{
           
            $('#flash_message').html("Delete Opration Failed");
            $('#flash_message').removeClass('alert-success');
            $('#flash_message').addClass('alert-danger');
            $('#flash_message').fadeIn();
            $('#flash_message').fadeOut(5000);
       }

    }, 'json'); //done 
   
});
/*
 * cancel order form set orderId to order_id in form
 * @param {type} order_id
 * @returns {undefined}
 */
function cancel_order_row(order_id)
{
   $('#order_id').val(order_id);
}
/*
 * Cancel the order using AJAX request
 */
$('.cancel_orders_modal_submit').click(function() {
     $('#cancel_order_modal').modal('hide');
    /*Form token set up*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }});
    
    /* Mail setting form id object*/
    $form = $('#cancel_order_form');
    /*Mail setting form data*/
    $data = $form.serialize();
    /*Mail setting from url*/
    url =$form.attr('action');
  
   var posting = $.post(url, {formData: $data});
    posting.done(function(data) {
        $("#pwdr").val('');
        if(data['message']=='success')
        {
            $("#order_row_"+$('#order_id').val()).remove();
            $('#flash_message').html("Order Cancel Successfully");
            $('#flash_message').removeClass('alert-danger');
            $('#flash_message').addClass('alert-success');
            $('#flash_message').fadeIn();
            $('#flash_message').fadeOut(5000);
        }
       else{
           
            $('#flash_message').html("Order Cancel Failed");
            $('#flash_message').removeClass('alert-success');
            $('#flash_message').addClass('alert-danger');
            $('#flash_message').fadeIn();
            $('#flash_message').fadeOut(5000);
       }

    }, 'json'); //done 
   
});
