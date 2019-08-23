$(document).ready(function () {

//    $(document).scroll(function () {
//        var y = $(this).scrollTop();
//        if (y > 200) {
//            $('.inventory_table_head').addClass("sticky_inventory");
//        } else {
//            $('.inventory_table_head').removeClass("sticky_inventory");
//        }
//    });

//    var current_url = window.location.href;
//    var split_url = current_url.split("/");
//    if (split_url[3] == 'delivery_order' && split_url[5] == 'edit') {
//        $('#add_product_row_delivery_order').trigger('click');
//    }
//    if (split_url[3] == 'orders' && split_url[5] == 'edit') {
//        $('#add_product_row').trigger('click');
//    }
//    if (split_url[3] == 'inquiry' && split_url[5] == 'edit') {
//        $('#add_product_row').trigger('click');
//    }
//    if (split_url[3] == 'create_delivery_order') {
//        $('#add_product_row_delivery_order').trigger('click');
//    }
//    if (split_url[3] == 'place_order') {
//        $('#add_product_row').trigger('click');
//    }
//    if (split_url[4] == 'delivery_order' && split_url[6] == 'edit') {
//        $('#add_product_row_delivery_order').trigger('click');
//    }
//    if (split_url[4] == 'orders' && split_url[6] == 'edit') {
//        $('#add_product_row').trigger('click');
//    }
//    if (split_url[4] == 'inquiry' && split_url[6] == 'edit') {
//        $('#add_product_row').trigger('click');
//    }
//    if (split_url[4] == 'create_delivery_order') {
//        $('#add_product_row_delivery_order').trigger('click');
//    }
//    if (split_url[4] == 'place_order') {
//        $('#add_product_row').trigger('click');
//    }
//    if (split_url[3] == 'purchase_orders' && split_url[5] == 'edit') {
////        $('#add_purchase_product_row').trigger('click');
////        jQuery('#add_purchase_product_row')[0].click();
//    }
//    if (split_url[4] == 'purchase_orders' && split_url[6] == 'edit') {
////        $('#add_purchase_product_row').trigger('click');
////        jQuery('#add_purchase_product_row')[0].click();
//    }
//    if (split_url[3] == 'create_purchase_advice') {
//        jQuery('#add_purchase_advice_product_row')[0].click();
//    }
//    if (split_url[4] == 'create_purchase_advice') {
//        jQuery('#add_purchase_advice_product_row')[0].click();
//    }
//    if (split_url[3] == 'purchaseorder_advise' && split_url[5] == 'edit') {
////        $('#add_purchase_product_row').trigger('click');
//        jQuery('#add_purchase_advice_product_row')[0].click();
//    }
//    if (split_url[4] == 'purchaseorder_advise' && split_url[6] == 'edit') {
////        $('#add_purchase_product_row').trigger('click');
//        jQuery('#add_purchase_advice_product_row')[0].click();
//    }

$(".btn_save_truck").click(function () {
    
    var $inputs = $('.dynamic_field :input');
     $inputs.each(function() {
    
        var weight = $(this).val();
        var textinput = $(this).attr('id');
        var delboy_id  = textinput.split("truck_weight");
        var delivery_id = $("#delivery_id").val();
        var empty_truck_weight = $("#empty_truck_weight").val();
     
         /* $.ajax({
                type: 'POST',
                url: url + '/truck_load_bydelboy',
                data: {
                    weight:weight,
                    delboy_id:delboy_id,
                    delivery_id:delivery_id,
                    empty_truck_weight:empty_truck_weight,
                },
                success: function (data) {
                    console.log("success");
                }
       
    });*/
});
 });
  $(".assign_order").click(function () {
	  var deliverid = $(this).data('delivery_id');
	   var supervisor_id = $(this).data('supervisor_id');
	    $(".del_supervisor").val(supervisor_id);
	  $(".modal-body #delivery_id").val( deliverid );
  });
 $(".assign_load").click(function () {
     
     var deliverid = $(this).data('delivery_id');
     var supervisor_id = $(this).data('supervisor_id');
     var delboy_id = $(this).data('delivery_boy');
     var roleid = $(this).data('role_id');
     console.log(supervisor_id);
     $(".modal-body #delivery_id").val( deliverid );
     if(roleid ==0){
        
            $(".del_supervisor").val(supervisor_id);
         
        //$(".del_supervisor").val(supervisor_id);
     }
     else{
        
            $(".del_supervisor").val(delboy_id);
         
        
     }
    // $(".del_supervisor").val(delboy_id);
    
    
});
    window.setTimeout(function () {
        $(".alert-autohide").fadeTo(1500, 0).slideUp(500, function () {
        });
    }, 5000);
    $('body').on('click', '#demodiv', function () {
        var baseurl = $('#baseurl').attr('name');
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType: 'jsonp',
            url: baseurl + "/postdemo",
            success: function (data) {
            }
        });
    });
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

        if (prod == 3) {
            $('.thick12').css('display', 'block');
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

    $("#select_product_categroy").on('change',function () {
        var id = this.value;
        var url = $('#baseurl2').val();
        var token = $('#_token').val();
        $.ajax({
            type: 'get',
            url: url + '/get_hsn_code',
            data: {id: id, _token: token},
            success: function (data) {
                $('#hsn_code').val(data);
            }
        });
    });


});
$(document).ready(function () {
    $('body').on('click', '.delete-purchase-order-daybook', function () {
        $("#delete_purchase_daybook_form").attr('action', $(this).attr('data-url'));
    });
    $('body').on('click', '.delete-sales-day-book', function () {
        $("#delete-sales-day-book-form").attr('action', $(this).attr('data-url'));
    });
    $(".flags").click(function () {
        if ($(this).hasClass('empty_star')) {
            $(this).removeClass('empty_star');
            $(this).addClass('filled_star');
        } else {
            $(this).removeClass('filled_star');
            $(this).addClass('empty_star');
        }
        var baseurl = $('#baseurl').attr('name');
        var module = $('#module').val();
        var order_id = $(this).attr('data-orderid');
        $.ajax({
            type: 'get',
            url: baseurl + '/flag_order',
            data: {order_id: order_id, module: module},
            success: function (data) {
            }
        });
    });
    var myRadio = $('input[name=customer_status]');
    var checkedValue = myRadio.filter(':checked').val();

    if (checkedValue == 'existing_customer') {
        $(".exist_field").hide();
        $(".customer_select").show();
    }

    $("#exist_customer").click(function () {
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#existing_customer").click(function () {

        $(".tabindex2").attr("tabindex", 2);
        $(".tabindex3").attr("tabindex", 3);
        $(".tabindex4").attr("tabindex", 4);
    });
    $("#new_customer").click(function () {
        $(".exist_field").show();
        $(".customer_select").hide();
        $(".tabindex2").attr("tabindex", 0);
        $(".tabindex3").attr("tabindex", 0);
        $(".tabindex4").attr("tabindex", 0);
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
    $("#add_product_row_delivery_challan").on("click", function () {
        var current_row_count = $(".add_product_row").length + 1;
        var vat_count = $(".add_product_row").length - 2;
        var isVAT = $("#product_vat_percentage_value_" + vat_count).val();
        var isChecked = 'checked';
        if (isVAT == 0)
            isChecked = '';
        var baseurl = $('#baseurl').attr('name');
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
                '<td class="col-md-2">' +
                '<div class="form-group searchproduct">' +
                '<input class="form-control each_product_detail" data-productid="' + current_row_count + '" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="delivery_challan_product_name_' + current_row_count + '" onfocus="delivery_challan_product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" id="product_weight_' + current_row_count + '" value="">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group">' +
                '<input id="actual_quantity_' + current_row_count + '" class="form-control delivery_challan_qty" placeholder="Qnty" name="product[' + current_row_count + '][actual_quantity]" value="" type="text" onblur="fetch_price();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
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
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]" onblur="fetch_price();">' +
                '</div>' +
                '</td>' +
                '<td class="col-md-1">' +
                '<div class="form-group inquiry_vat_chkbox">' +
                '<input type="checkbox" class="vat_chkbox" name="product[' + current_row_count + '][vat_percentage]" disabled="" ' + isChecked + ' value="yes">' +
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
                '<div id="amount_' + current_row_count + '">0</div>' +
                '</div>' +
                '</td>' +
                '<input type="hidden" name="product[' + current_row_count + '][order]" value="">' +
                '</tr>';
        $("#add_product_table_delivery_challan").children("tbody").append(html);
    });
    $('.delete_completed').on('click', function () {
        if (this.checked) {
            $('.checkBoxClass').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkBoxClass').each(function () {
                this.checked = false;
            });
        }
    });
    $('.checkBoxClass').on('click', function () {
        if ($('.checkBoxClass:checked').length == $('.checkBoxClass').length) {
            $('.delete_completed').prop('checked', true);
        } else {
            $('.delete_completed').prop('checked', false);
        }
    });
    $('.save_all_inventory').on("click", function (e) {
        $('#frm_inventory_save_all').submit();
    });
    $('.delete_records_modal').on("click", function (e) {
        if ($('#password_delete_completetd').val().trim().length == 0) {
            $('.delete_records_empty').text("Please enter your password");
            $('.delete_records_empty').css("display", "block");
            $('.delete_records_empty').css("opacity", "1");
            $('.delete_records_empty').focus();
        } else {
            $('#password_delete').val($('#password_delete_completetd').val());
            $('#is_delete_all').attr('value','');
            $('#frmdeleterecords').submit();
        }
    });
    $('.submit_delete_all').on("click", function (e) {
        e.preventDefault();
        var checkedAtLeastOne = false;
        $('.checkBoxClass').each(function () {
            if ($(this).is(":checked")) {
                checkedAtLeastOne = true;
            }
        });
//        $('#password_delete_completetd').val().trim().length
//            $('#empty_select_completed').text("Please enter your password");
//            $('#empty_select_completed').css("display", "block");
//            $('#empty_select_completed').css("opacity", "1");
//            $('#password_delete_completetd').focus();
//            window.setTimeout(function () {
//                $('#empty_select_completed').fadeTo(1500, 0).slideUp(500, function () {
//                });
//            }, 5000);
//        }
        if (checkedAtLeastOne) {
            $('#delete_records_modal').modal('show');
//            $(this).closest("form").unbind("submit");
//            $(this).closest("form").submit();
        } else {
            $('#empty_select_completed').text("Please select atleast one record from the list");
            $('#empty_select_completed').css("display", "block");
            $('#empty_select_completed').css("opacity", "1");
            window.setTimeout(function () {
                $('#empty_select_completed').fadeTo(1500, 0).slideUp(500, function () {
                });
            }, 5000);
        }
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
                '<input class="form-control each_product_detail" data-productid="' + current_row_count + '"  placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_purchase_product_name_' + current_row_count + '" onfocus="product_autocomplete_purchase(' + current_row_count + ');">' +
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
$(function () {
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
    }).done(function (data) {
        var main_array = JSON.parse(data);
        var arr1 = main_array['units'];
        var html = '';
        for (var key in arr1) {
            html += '<option value="' + arr1[key].id + '">' + arr1[key].unit_name + '</option>';
        }
        $("#units_" + current_row_count).html(html);
    });

    $("#table-example").children("tbody").append(str);
}

/**
 * calutate_pending_order
 */
function calutate_pending_order(qty, key) {

    var shipping = $('#present_shipping' + key).val();
    if (shipping == "") {
        shipping = 0;
    }
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


$('#onenter_prevent input,#onenter_prevent textarea').keypress(function (e) {
    if (e.which == 13) {
        return false;
    }
});

//$('#pwdr').keypress(function (e) {
//    if (e.which == 13) {
//        $('.delete_orders_modal_submit').focus();
//        return false;
//    }
//});

$('form#onenter_prevent').keypress(function (e) {

    if (e.which == 13) {
        submit_button_id = $(this).attr('data-button');
        if (submit_button_id) {
            $('.' + submit_button_id).trigger('click');
        } else {
            return false;
        }
    }
});

$('body').delegate("#add_order_location", "blur", function () {
    if ($(this).val() == '0') {
        $(this).addClass('error_validation');
    } else {
        $(this).removeClass('error_validation');
    }
});
$('body').delegate(".btn_add_inquiry, .btn_add_inquiry_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#customer_name').val() == "") {
            $('#customer_name').addClass('error_validation');
            status_form = 1;
        }else{
            $('#customer_name').removeClass('error_validation');
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }else{
            $('#add_order_location').removeClass('error_validation');
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }else{
            $('#contact_person').removeClass('error_validation');
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }else{
            $('#mobile_number').removeClass('error_validation');
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }else{
            $('#period').removeClass('error_validation');
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                     $('#product_all_' + i).addClass('error_validation');
                    status_form = 1;
                }else{
                    $('#product_all_' + i).removeClass('error_validation');
                     status_form = 0;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }

        if ($("#add_product_name_1").val() != "") {
//            console.log(tot_products,j);
            $('#add_product_name_1').removeClass('error_validation');
            $('#product_all_1').removeClass('error_validation');
        }

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {

        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
//            $('#existing_customer_name').addClass('error_validation');
            $('#existing_customer_name').closest('.searchproduct').find('.custom-combobox-input').addClass('error_validation');
            status_form = 1;
        }else{
            $('#existing_customer_name').closest('.searchproduct').find('.custom-combobox-input').removeClass('error_validation');
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }else{
            $('#add_order_location').removeClass('error_validation');
        }


        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        });
//        alert(status_form);
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    $('#product_all_' + i).addClass('error_validation');
                    status_form = 1;
                }else{
                    $('#product_all_' + i).removeClass('error_validation');
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
                $('#product_all_1').addClass('error_validation');
            }else{
                $('#product_all_1').removeClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }

        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_edit_inquiry, .btn_edit_inquiry_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }else{
            $('#name').removeClass('error_validation');
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }else{
            $('#contact_person').removeClass('error_validation');
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }else{
            $('#mobile_number').removeClass('error_validation');
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }else{
            $('#add_order_location').removeClass('error_validation');
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }else{
            $('#period').removeClass('error_validation');
        }

        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
//        alert(status_form);
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_0").val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "edit_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_inquiry_location').val() == '0') {
            $('#add_inquiry_location').addClass('error_validation');
            status_form = 1;
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
//        alert(status_form);
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_0").val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }

        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "edit_inquiry_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_add_order, .btn_add_order_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }else{$('#name').removeClass('error_validation');}
        if ($('#add_order_location').val() == "") {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }else{$('#add_order_location').removeClass('error_validation');}
        if ($('#contact_person').val() == "") {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }else{$('#contact_person').removeClass('error_validation');}
        if ($('#mobile_number').val() == "") {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }else{$('#period').removeClass('error_validation');}
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
        // if (CheckBoxArray.length == 0)
        // {
        //     $('#vat_percentage').removeClass('error_validation');
        //     status_form = 0;
        // }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    $('#product_all_' + i).addClass('error_validation');
                    status_form = 1;
                } else {
                    $('#product_all_' + i).removeClass('error_validation');
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == 0) {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }

//         for (i = 1; i <= tot_products; i++) {

//             if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "" | $("#quantity_" + i).val() == "0" | $("#quantity_" + i).val() == 0)) {
//                 if ($("#quantity_" + i).val() == "" | $("#quantity_" + i).val() == "0" | $("#quantity_" + i).val() == 0) {

//                     j++;
//                 }
//             } else {

//                 if ($("#add_product_id_" + i).val() == "") {
//                     if (i != tot_products) {
//                         $('#add_product_name_' + i).addClass('error_validation');
//                         status_form = 1;

//                     }
//                 }
// //                if ($('#existing_customer_name').val() == "") {
// //                    if (i != tot_products) {
// //                        $('#add_product_name_' + i).addClass('error_validation');
// //                        status_form = 1;
// //                    }
// //                }
//                 if ($("#quantity_" + i).val() == "" | $("#quantity_" + i).val() == "0" | $("#quantity_" + i).val() == 0) {
//                     if (i != tot_products) {
//                         $('#quantity_' + i).addClass('error_validation');
//                         status_form = 1;

//                     }
//                 }
//             }
//         }

        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }

        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }

        if (status_form == 1) {
            $('#flash_error').css('display','none');
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 400);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            $('.uploader').addClass('error_validation');
            status_form = 1;
        } else {
            $('.uploader').removeClass('error_validation');
        }
        if ($('#add_order_location').val() == '') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    $('#product_all_' + i).addClass('error_validation');
                    status_form = 1;
                } else {
                    $('#product_all_' + i).removeClass('error_validation');
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == 0) {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }

        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
                $('#product_all_1').addClass('error_validation');
            } else {
                $('#product_all_1').removeClass('error_validation');
            }
            if ($("#quantity_1").val() == 0) {
                $('#quantity_1').addClass('error_validation');
            }

            status_form = 1;
        }
        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }

        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_edit_order, .btn_edit_order_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
//        alert(status_form);
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if ($("#add_order_location").val() == "other") {
            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            }


        });
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#product_price_" + i).val() == "") {
                    $('#product_price_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
//        if (j == tot_products) {
//            if ($("#add_product_id_1").val() == "") {
//                $('#add_product_name_1').addClass('error_validation');
//            }
//            if ($("#quantity_1").val() == "") {
//                $('#quantity_1').addClass('error_validation');
//            }
//            status_form = 1;
//        }

        if ($("#add_order_location").val() == "other") {
            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "edit_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$('body').delegate(".btn_edit_delivery_order", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
            console.log('#name' + status_form);
        }


        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
            console.log('#add_order_location' + status_form);
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        var present_shippein_zero_count = 0;
        for (i = 0; i <= tot_products + 1; i++) {
            if ($("#present_shipping_" + i).val() == 0) {
                present_shippein_zero_count++;
            }
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                    console.log('#add_product_name_' + i + "--" + status_form);
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                    console.log('#quantity_' + i + "--" + status_form);
                }
            }
        }
        if (tot_products == present_shippein_zero_count) {
            for (var j = 0; j <= tot_products; j++) {
                $('#present_shipping_' + j).addClass('error_validation');
            }
            status_form = 1;
        }

        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }

        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }


        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            }


        });
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        var present_shippein_zero_count = 0;
        for (i = 0; i <= tot_products + 1; i++) {
            if ($("#present_shipping_" + i).val() == 0) {
                present_shippein_zero_count++;
            }
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#product_price_" + i).val() == "") {
                    $('#product_price_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (tot_products == present_shippein_zero_count) {
            for (var j = 0; j <= tot_products; j++) {
                $('#present_shipping_' + j).addClass('error_validation');
            }
            status_form = 1;
        }

        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            $(this).parents('form').submit();
        }
    }

});
$('body').delegate(".btn_add_delivery_order", "click", function () {
    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }
        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });
        if (CheckBoxArray.length == 0)
        {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }



        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            $('#onenter_prevent').submit();
        }

    } else {
        if ($('#existing_customer_id').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }

        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        });
        if (CheckBoxArray.length == 0) {
            $('#vat_percentage').removeClass('error_validation');
            if (status_form != 1)
                status_form = 0;
        }

        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 1; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_1").val() == "") {
                $('#add_product_name_1').addClass('error_validation');
            }
            if ($("#quantity_1").val() == "") {
                $('#quantity_1').addClass('error_validation');
            }
            status_form = 1;
        }

        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            $('#onenter_prevent').submit();
        }
    }

});

$('body').delegate(".btn_edit_delivery_challan", "click", function () {
    if ($("#challan_vehicle_number").val() == "") {
        $('#challan_vehicle_number').addClass('error_validation');
        status_form = 1;
    }
    else {
        $('#challan_vehicle_number').removeClass('error_validation');
        status_form = 0;
    }
    var tot_products = $(".add_product_row:visible").length;
    var j = 0;
    var empty_truck_weight = parseInt($('#empty_truck_weight').val());
    var order_source = $('#order_source').val();
    var final_truck_weight = parseInt(final_truck_weight - empty_truck_weight);
    var total_actual_qty_truck = parseInt($('#total_actual_qty_truck').val());
    var total_actual_quantity = parseInt($('#total_actual_quantity').val());
    if(order_source!="supplier"){
        if (empty_truck_weight == "" | empty_truck_weight == 0 | empty_truck_weight == "0") {
            status_form = 1;
            $('#empty_truck_weight').addClass('error_validation');
        }
    }
    if (final_truck_weight == "" | final_truck_weight == 0 | final_truck_weight == "0" | final_truck_weight <= empty_truck_weight) {
        status_form = 1;
        $('#final_truck_weight').addClass('error_validation');
    }
//    if (total_actual_qty_truck != total_actual_quantity) {
//        status_form = 1;
//        $('#final_truck_weight').addClass('error_validation');
//    }

    for (i = 1; i <= tot_products - 1; i++) {
        if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "") {
                $('#add_product_name_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#quantity_" + i).val() == "") {
                $('#quantity_' + i).addClass('error_validation');
                status_form = 1;
            }
        }
    }

    for (i = 1; i <= tot_products; i++) {
        var actual_qty = parseInt($("#actual_quantity_" + i).val());
        var actual_pieces = parseInt($("#actual_pieces_" + i).val());
        var price = parseInt($("#product_price_" + i).val());
        if ($("#actual_quantity_" + i).val() == "" || actual_qty == "0") {
            $('#actual_quantity_' + i).addClass('error_validation');
            status_form = 1;
        }
        if ($("#actual_pieces_" + i).val() == "" || actual_pieces == "0") {
            $('#actual_pieces_' + i).addClass('error_validation');
            status_form = 1;
        }
        if ($("#product_price_" + i).val() == "" || price == "0") {
            $('#product_price_' + i).addClass('error_validation');
            status_form = 1;
        }
    }


    if (j == tot_products) {
        if ($("#add_product_id_1").val() == "") {
            $('#add_product_name_1').addClass('error_validation');
        }
        if ($("#quantity_1").val() == "") {
            $('#quantity_1').addClass('error_validation');
        }
        status_form = 1;
    }
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});


$('body').delegate(".btn_puradvice_to_purchallan", "click", function () {

    var status_form = 0;
    var tot_products = $(".add_product_row").length;
    var j = 0;
    for (i = 0; i <= tot_products; i++) {
        if (($("#add_product_id_" + i).val() == "") && ($("#actual_quantity_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "") {
                $('#add_product_name_' + i).addClass('error_validation');
                status_form = 1;
            } else {
                if ($('#add_purchase_product_name_' + i).val() == "") {
                    $('#add_purchase_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#shipping_" + i).val() == "") {
                    $('#shipping_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#actual_quantity_" + i).val() == "") {
                    $('#actual_quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#product_price_" + i).val() == "") {
                    $('#product_price_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
    }
    if ($('#freight').val() == "") {
        $('#freight').addClass('error_validation');
        status_form = 1;
    }
    if ($('#loadedby').val() == "") {
        $('#loadedby').addClass('error_validation');
        status_form = 1;
    }
    if ($('#labour').val() == "") {
        $('#labour').addClass('error_validation');
        status_form = 1;
    }
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_purorder_to_puradvice", "click", function () {
    var status_form = 0;
    var tot_products = $(".add_product_row").length;
    var j = 0;
    var present_shippein_zero_count = 0;
    var actual_pieces_count = 0;
    for (var i = 0; i <= tot_products - 1; i++) {
        if ($("#actual_pieces" + i).val() == 0 | $("#actual_pieces" + i).val() == "") {
            actual_pieces_count++;
        }
    }
    if ((tot_products - 1) == actual_pieces_count) {
        for (var j = 1; j <= tot_products - 1; j++) {
            $('#actual_pieces' + j).addClass('error_validation');
        }
        status_form = 1;
    } else {
        for (var j = 1; j <= tot_products - 1; j++) {
            $('#actual_pieces' + j).removeClass('error_validation');
        }
    }

    for (var i = 0; i <= tot_products - 1; i++) {
        if ($("#present_shipping" + i).val() == 0 | $("#present_shipping" + i).val() == "") {
            present_shippein_zero_count++;
        }
    }
    if ((tot_products - 1) == present_shippein_zero_count) {
        for (var j = 1; j <= tot_products - 1; j++) {
            $('#present_shipping' + j).addClass('error_validation');
        }
        status_form = 1;
    } else {
        for (var j = 1; j <= tot_products - 1; j++) {
            $('#present_shipping' + j).removeClass('error_validation');
        }
    }


    if ($("#vehicle_number").val() == "") {
        $("#vehicle_number").addClass('error_validation');
        status_form = 1;
    } else {
        $("#vehicle_number").removeClass('error_validation');
    }
    if ($("#datepickerDate").val() == "") {
        $("#datepickerDate").addClass('error_validation');
        status_form = 1;
    } else {
        $("#datepickerDate").removeClass('error_validation');
    }

    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_delorderto_delchallan", "click", function () {
    var status_form = 0;
    var tot_products = $(".add_product_row").length;
//    var tot_products = $(".add_product_row:visible").length;
    var j = 0;
    var order_source = $('#order_source').val();
    var empty_truck_weight = parseInt($('#empty_truck_weight').val());
    var final_truck_weight = parseInt($('#final_truck_weight').val());
    var final_truck_weight = parseInt($('#final_truck_weight').val());
    var total_actual_qty_truck = parseInt($('#total_actual_qty_truck').val());
    var total_avg_qty = parseInt($('#total_avg_qty').val());

    if(order_source != "supplier"){
        if (empty_truck_weight == "" | empty_truck_weight == 0 | empty_truck_weight == "0") {
            status_form = 1;
            $('#empty_truck_weight').addClass('error_validation');
        }
    }

    if ((final_truck_weight == "" | final_truck_weight == 0 | final_truck_weight == "0" | final_truck_weight <= empty_truck_weight)) {
        status_form = 1;
        $('#final_truck_weight').addClass('error_validation');
    }

//    if(total_avg_qty!=0 && total_actual_qty_truck!=0){
//        var total_avg_qty_percent = total_avg_qty*10/100;
//        var total_actual_qty_truck_percent = total_actual_qty_truck*10/100;
//
//        var discount_price1 = total_actual_qty_truck + total_actual_qty_truck_percent;
//        var discount_price2 = total_actual_qty_truck - total_actual_qty_truck_percent;
//        var discount_price3 = total_actual_qty_truck + total_actual_qty_truck_percent;
//        var discount_price4 = total_actual_qty_truck - total_actual_qty_truck_percent;
//
//        if(total_avg_qty>discount_price1 || total_avg_qty<discount_price2){
//            status_form = 1;
//            $('#final_truck_weight').addClass('error_validation');
//        }
//    }

    if(total_avg_qty > total_actual_qty_truck){
        var total_avg_qty_percent = total_avg_qty*10/100;
        var discount_price1 = total_avg_qty + total_avg_qty_percent;
        var discount_price2 = total_avg_qty - total_avg_qty_percent;

        if(total_actual_qty_truck>discount_price1 || total_actual_qty_truck<discount_price2){
            status_form = 1;
            $('#final_truck_weight').addClass('error_validation');
        }
    }else if(total_avg_qty < total_actual_qty_truck){
        var total_actual_qty_truck_percent = total_actual_qty_truck*10/100;
        var discount_price1 = total_actual_qty_truck + total_actual_qty_truck_percent;
        var discount_price2 = total_actual_qty_truck - total_actual_qty_truck_percent;

        if(total_avg_qty>discount_price1 || total_avg_qty<discount_price2){
            status_form = 1;
            $('#final_truck_weight').addClass('error_validation');
        }
    }


    for (i = 1; i <= tot_products; i++) {
        if (($("#add_product_id_" + i).val() == "") && ($("#actual_quantity_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "") {
                $('#add_product_name_' + i).addClass('error_validation');
                status_form = 1;
            } else {
                if ($('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
            if ($("#present_shipping_" + i).val() == "" && $("#add_row_" + i).css('display')!='none') {
                $('#present_shipping_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#actual_quantity_" + i).val() == "" && $("#add_row_" + i).css('display')!='none') {
                $('#actual_quantity_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#product_price_" + i).val() == "" && $("#add_row_" + i).css('display')!='none' ) {
                $('#product_price_' + i).addClass('error_validation');
                status_form = 1;
            }
        }
        if ($("#challan_vehicle_number").val() == "") {
            $('#challan_vehicle_number').addClass('error_validation');
            status_form = 1;
        }

        if ($("#total_actual_qty").val() == "") {
            $('#total_actual_qty').addClass('error_validation');
            status_form = 1;
        }

        if ($("#total_actual_qty").hasClass("error_validation"))
        {
            status_form = 1;
        }
    }
//    if (j == tot_products) {
//        for (i = 0; i <= tot_products; i++) {
//            $('#add_product_name_' + i).addClass('error_validation');
//        }
//        status_form = 1;
//    }
//    alert(status_form);
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
//$('body').delegate(".existing_customer_order", "click", function () {
//    if ($(this).attr('checked') == 'checked') {
//    } else {
//        $('.existing_customer_order').attr('checked', '');
//        $(this).attr('checked', 'checked');
//    }
//});
//$('body').delegate(".new_customer_order", "click", function () {
//    if ($(this).attr('checked') == 'checked') {
//    } else {
//        $('.existing_customer_order').attr('checked', '');
//        $(this).attr('checked', 'checked');
//    }
//});
$('body').delegate(".btn_order_to_delorder", "click", function () {

    var status_form = 0;
//    if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
//        $('#existing_customer_name').addClass('error_validation');
//        status_form = 1;
//    }
//    if ($('#add_order_location').val() == '0') {
//        $('#add_order_location').addClass('error_validation');
//        status_form = 1;
//    }
    var tot_products = $(".add_product_row").length;
    var j = 0;
    var present_shippein_zero_count = 0;

    for (var i = 0; i <= tot_products + 1; i++) {
        if ($("#present_shipping_" + i).val() == 0) {
            present_shippein_zero_count++;
        }
        if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
            j++;
        } else {
            if ($("#add_product_id_" + i).val() == "") {
                $('#add_product_name_' + i).addClass('error_validation');
                status_form = 1;
            } else {
                if ($('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
            if ($("#present_shipping_" + i).val() == "") {
                $('#present_shipping_' + i).addClass('error_validation');
                status_form = 1;
            }

            if ($("#quantity_" + i).val() == "") {
                $('#quantity_' + i).addClass('error_validation');
                status_form = 1;
            }
            if ($("#quantity_" + i).val() == 0) {
                $('#quantity_' + i).addClass('error_validation');
                status_form = 1;
            }
        }
    }
    if (tot_products == present_shippein_zero_count) {
        for (var j = 0; j <= tot_products; j++) {
            $('#present_shipping_' + j).addClass('error_validation');
        }
        status_form = 1;
    }



//    if (j == tot_products) {
//        for (i = 0; i <= tot_products+1; i++) {
//            $('#add_product_name_' + i).addClass('error_validation');
//        }
//        status_form = 1;
//    }
//    alert(status_form);
    if (status_form == 1) {
        $('html, body').animate({
            scrollTop: $('.breadcrumb').offset().top
        }, 1000);
        return false;
    } else {
        $('#onenter_prevent').submit();
    }
});
$('body').delegate(".btn_inquiry_to_order, .btn_inquiry_to_order_sms", "click", function () {

    var status_form = 0;
    if ($('input[name=customer_status]:checked').val() == "new_customer") {
        if ($('#name').val() == "") {
            $('#name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }
        if ($('#contact_person').val() == '') {
            $('#contact_person').addClass('error_validation');
            status_form = 1;
        }
        if ($('#mobile_number').val() == '') {
            $('#mobile_number').addClass('error_validation');
            status_form = 1;
        }
        if ($('#period').val() == '') {
            $('#period').addClass('error_validation');
            status_form = 1;
        }

        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        });
        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_0").val() == "" || $('#add_product_name_0').val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                status_form = 0;
            }
        }

//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_to_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }

    } else {
        if ($('#existing_customer_id').val() == "" || $('#existing_customer_name').val() == "") {
            $('#existing_customer_name').addClass('error_validation');
            status_form = 1;
        }
        if ($('#add_order_location').val() == '0') {
            $('#add_order_location').addClass('error_validation');
            status_form = 1;
        }

        CheckBoxArray = [];
        $("input:checkbox[class='vat_chkbox']:checked").each(function () {
            CheckBoxArray.push($(this).val());
            if ($('#vat_percentage').val() == "" | $('#vat_percentage').val() == "0") {
                $('#vat_percentage').addClass('error_validation');
                status_form = 1;
            } else {
                $('#vat_percentage').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }


        });

        var tot_products = $(".add_product_row").length;
        var j = 0;
        for (i = 0; i <= tot_products; i++) {
            if (($("#add_product_id_" + i).val() == "") && ($("#quantity_" + i).val() == "")) {
                j++;
            } else {
                if ($("#add_product_id_" + i).val() == "" || $('#add_product_name_' + i).val() == "") {
                    $('#add_product_name_' + i).addClass('error_validation');
                    status_form = 1;
                }
                if ($("#quantity_" + i).val() == "") {
                    $('#quantity_' + i).addClass('error_validation');
                    status_form = 1;
                }
            }
        }
        if (j == tot_products) {
            if ($("#add_product_id_0").val() == "" || $('#add_product_name_0').val() == "") {
                $('#add_product_name_0').addClass('error_validation');
            }
            if ($("#quantity_0").val() == "") {
                $('#quantity_0').addClass('error_validation');
            }
            status_form = 1;
        }
        if ($("#add_order_location").val() == "other") {

            if ($("#location_difference").val() == "") {
                $('#location_difference').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location_difference').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }

            if ($("#location").val() == "") {
                $('#location').addClass('error_validation');
                status_form = 1;
            } else {
                $('#location').removeClass('error_validation');
                if (status_form != 1)
                    status_form = 0;
            }
        }
//        alert(status_form);
        if (status_form == 1) {
            $('html, body').animate({
                scrollTop: $('.breadcrumb').offset().top
            }, 1000);
            return false;
        } else {
            var curid = $(this).attr("id");
            if (curid == "add_inquiry_to_order_sendSMS") {
                var action = $(this).parents('form').attr('action');
                $(this).parents('form').attr('action', action + '?sendsms=true');
                $(this).parents('form').submit();
            } else {
                $(this).parents('form').submit();
            }
        }
    }

});
$("#product_size").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    position: {
//        my: "left bottom",
//        at: "left top"
    },
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
    },
    close: function (event, ui) {
        $(this).closest("form").submit();
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
        $("#order_size").val(ui.item.value);
        $("#order_size_temp").val(ui.item.id);
    },
    close: function (event, ui) {
        $(this).closest("form").submit();
    }
});
$("#search_text").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    position: {
//        my: "left bottom",
//        at: "left top"
    },
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
    },
    close: function (event, ui) {
        $(this).closest("form").submit();
    }
});
$('#save_all_price_btn').click(function () {

    $.ajax({
        type: 'post', url: baseurl + '/update_all_price',
        data: $('#save_all_price').serialize(),
        success: function (data) {
            $('html,body').animate({scrollTop: 0}, 'slow');
            $('.alert-success1').show();
        }
    });
});
$("#search_inventory").autocomplete({
    minLength: 1,
    dataType: 'json',
    type: 'GET',
    source: function (request, response) {
        $("#search_inventory").addClass('loadinggif');
        $.ajax({
            url: baseurl + '/fetch_inventory_product_name',
            data: {"term": request.term},
            success: function (data) {
                var main_array = JSON.parse(data);
                var arr1 = main_array['data_array'];
                response(arr1);
                $("#search_inventory").removeClass('loadinggif');
            },
        });
    },
    select: function (event, ui) {
//        console.log(ui.item.label);
        $("#search_inventory").val(ui.item.label);
    },
    close: function (event, ui) {
        $(this).closest("form").submit();
    }
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

//function test() {
////    var abc = $(e).val();
////    $(e).val(function(i, abc) {
////        return abc.replace(/\d{3}|[^\d{2}\.]|^\./g, "");
////    });
//    return isNumber(event, this);
//}

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
$('#delete_records_modal').on('hidden.bs.modal', function () {
    $('#password_delete_completetd').val();
    $('.delete_records_empty').css('display', 'none');
});
$('#save_all_size_btn').click(function () {
    var token = $('#_token').val();
    var pageid = $(this).attr('data-pageid');
    $.ajax({
        type: 'POST',
        url: baseurl + '/update_all_sizes',
        data: {form_data: $('#save_all_product_sizes').serialize(), _token: token, pageid: pageid},
        success: function (data) {
            $('.alert-success1').show();
            $('html, body').animate({
                scrollTop: $('.navbar-brand').offset().top
            }, 1000);
        }
    });
});
$('body').delegate(".pendingpadvice", "click", function () {
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
$('body').delegate("#redirect_url_for_sorting", "click", function () {
    var href = $('#redirect_url_for_sorting').attr("href");
    window.parent.location.href = href;
});
$('body').delegate("#existing_customer_name", "blur", function () {
    var cur_customer_tally_name = $('#existing_customer_id').val();
    if (cur_customer_tally_name == "") {
        $(this).focus();
        $(this).addClass('error_validation');
    } else {
        $(this).removeClass('error_validation');
    }

    if ($('#add_order_location').val() != '0') {
        $('#add_order_location').removeClass('error_validation');
    }
});
$('body').delegate("#name", "blur", function () {
    if ($('#name').val() == "") {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClass('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
        $(this).removeClass('error_validation');
    }
});
$('body').delegate("#existing_supplier_name", "blur", function () {
    if (($('#existing_supplier_name').val() == "") || ($('#existing_supplier_id').val() == "")) {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClaaa('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
    }
});
$('body').delegate(".each_product_qty", "blur", function () {
    var cur_product_id = $(this).attr("data-productid");
    if ($('#add_product_name_' + cur_product_id).val() != "") {
        if ($('#quantity_' + cur_product_id).val() == "") {
            $(this).focus();
            $(this).addClass('error_validation');
        } else {
            $(this).removeClass('error_validation');
        }
    }
});
$('body').delegate(".each_product_detail", "blur", function () {
    var current_product = $(this).val()
    var cur_product_id = $(this).attr("data-productid");
    if (current_product == "") {
//        $(this).focus();
//        $(this).css('border-color', 'red');

        $('#add_product_id_' + cur_product_id).val('');
        $('#add_product_id_' + cur_product_id).attr('data-curname', '');
    } else {
        var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
        if (related_cur_product_id == "") {
            $(this).focus();
            $(this).css('border-color', 'red');
            $(this).css('box-shadow', 'none');
            $(this).addClass('error_validation');
        } else {
            $(this).css('border-color', '#e7ebee');
            $(this).removeClass('error_validation');
        }
    }
});
$(".no_alphabets").keydown(function (event) {
    if ((event.keyCode >= 65) && (event.keyCode <= 90)) {
        return false;
    }
});
$('body').delegate(".each_product_detail_edit", "blur", function () {
    var current_product = $(this).val()
    var cur_product_id = $(this).attr("data-productid");
    var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
    if (related_cur_product_id == "") {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClass('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
        $(this).removeClass('error_validation');
    }
});

$('body').delegate("#vat_percentage", "blur", function () {
    var vat_percentage = $(this).val()
    //var cur_product_id = $(this).attr("data-productid");
    //var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
    if (vat_percentage == "") {
//        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
//        $(this).addClass('error_validation');
    } else {
        $(this).css('border-color', '#e7ebee');
//        $(this).removeClass('error_validation');
    }
});

$('body').delegate("#location", "blur", function () {
    var location = $(this).val()
    //var cur_product_id = $(this).attr("data-productid");
    //var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
    if (location == "") {
        $(this).focus();
        $(this).css('border-color', 'red');
        $(this).css('box-shadow', 'none');
        $(this).addClass('error_validation');

    } else {
        $(this).css('border-color', '#e7ebee');
        $(this).removeClass('error_validation');
    }
});

$('body').delegate("#location_difference", "blur", function () {
    var location_difference = $(this).val()
    //var cur_product_id = $(this).attr("data-productid");
    //var related_cur_product_id = $('#add_product_id_' + cur_product_id).val();
    if (location_difference == "") {

    } else {
        $(this).css('border-color', '#e7ebee');
        $(this).removeClass('error_validation');
    }
});

$('body').delegate(".pendingorder", "click", function () {
    var column_name = $(this).attr("data-column");
    var url = $('#base_url').val();
    var sortfield = $('#pending_order_sortfield').val();
    var sortfieldby = $('#pending_order_sortfieldby').val();
    console.log(sortfieldby);
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
        success: function (data) {
//            $('.custom_alert_success').fadeOut(5000);
            $('.alert-success1').show();
            $('html, body').animate({
                scrollTop: $('.navbar-brand').offset().top
            }, 1000);
        }
    });
}
function update_inventory(e, value) {
    $('.inventory_update_min,.inventory_update_max,.inventory_update').css('display', 'none');
    var id = value;
    var opening_stock = $("input[name=" + id + "]").val();
    var minimal = $('#minimal_' + id).val();
    if (opening_stock < 0) {
        $('.inventory_update_min').css('display', 'block');
        $('.inventory_update_min').css('opacity', 1);
        window.setTimeout(function () {
            $(".inventory_update_min").fadeTo(1500, 0).slideUp(500, function () {
            });
        }, 5000);
    } else if (minimal < 0) {
        $('.inventory_update_min').html('<strong>Error!</strong> Negatives values are not allowed in Minimum stock.');
        $('.inventory_update_min').css('display', 'block');
        $('.inventory_update_min').css('opacity', 1);
        window.setTimeout(function () {
            $(".inventory_update_min").fadeTo(1500, 0).slideUp(500, function () {
            });
        }, 5000);
    } else {
        var result = opening_stock.split(".");
        if (result[0].length > 6) {
            $('.inventory_update_max').css('display', 'block');
            $('.inventory_update_max').css('opacity', 1);
            window.setTimeout(function () {
                $(".inventory_update_max").fadeTo(1500, 0).slideUp(500, function () {
                });
            }, 5000);
        } else {
            $.ajax({
                type: 'get',
                url: baseurl + '/update_inventory',
                data: {opening_stock: opening_stock, minimal: minimal, id: id},
                success: function (data) {
                    var response_array = data;
                    var response_array = JSON.parse(data);
                    if (response_array['class'] == 'yes') {
                        $('#minimal_' + id).parent().parent().addClass('minimum_reach');
                    } else {
                        $('#minimal_' + id).parent().parent().removeClass('minimum_reach');
                    }
                    $('#sales_challan_' + id).text(response_array['sales_challan_qty']);
                    $('#purchase_challan_' + id).text(response_array['purchase_challan_qty']);
                    $('#physical_closing_' + id).text(response_array['physical_closing_qty']);
                    $('#pending_order_' + id).text(response_array['pending_sales_order_qty']);
                    $('#pending_deliver_order_' + id).text(response_array['pending_delivery_order_qty']);
                    $('#pending_purchase_order_' + id).text(response_array['pending_purchase_order_qty']);
                    $('#pending_purchase_advise_' + id).text(response_array['pending_purchase_advise_qty']);
                    $('#virtual_qty_' + id).text(response_array['virtual_qty']);
                    $('.datadisplay_' + id).effect('highlight', {color: "yellow"}, 5000);
                    $('.inventory_update').css('display', 'block');
                    $('.inventory_update').css('opacity', 1);
                    window.setTimeout(function () {
                        $(".inventory_update").fadeTo(1500, 0).slideUp(500, function () {
                        });
                    }, 4000);
                }
            });
        }

    }



    $('html, body').animate({
        scrollTop: $('.navbar-brand').offset().top
    }, 1000);
}
$(function () {
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                    .addClass("custom-combobox")
                    .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },
        _createAutocomplete: function () {
            var selected = this.element.children(":selected");
//               alert(this.element.val());
            value = this.element.val();
//         this.element.val() value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                    .appendTo(this.wrapper)
                    .val(value)
                    .attr("title", "")
                    .attr("tabindex", "1")
                    .attr("placeholder", "Enter tally name")
                    .addClass(" custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left focus_on_enter uploader")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy(this, "_source")
                    })
                    .tooltip({
                        classes: {
                            "ui-tooltip": "ui-state-highlight"
                        }
                    });

            this._on(this.input, {
                autocompleteselect: function (event, ui) {

                    var term = ui.item.value;

                    $.ajax({
//                beforeSend: function() {
//                    $.blockUI({message: '<img src="' + baseurl + '/resources/assets/img/loading.gif" width="20" />'});
//                },
                        url: baseurl + '/fetch_existing_customer',
                        data: {"term": term},
                        cache: true,
                        success: function (data) {
                            var obj = jQuery.parseJSON(data);
                            $("#existing_customer_id").val(obj.data_array[0].id);
                            $("#existing_customer_name").val(obj.data_array[0].value);
                            $("#customer_default_location").val(obj.data_array[0].delivery_location_id);
                            $("#location_difference").val(obj.data_array[0].delivery_location.difference);
                            default_delivery_location();
//                    $.unblockUI({message: '<img src="' + baseurl + '/resources/assets/img/loading.gif" width="20" />'});
                        },
                    });
                },
                autocompletechange: "_removeIfInvalid"
            });
        },
        _createShowAllButton: function () {
            var input = this.input,
                    wasOpen = false;

            $("<a>")
                    .attr("tabIndex", -1)
                    //.attr( "title", "Show All Items" )
                    //.tooltip()
                    .appendTo(this.wrapper)
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass("ui-corner-all")
                    .addClass("custom-combobox-toggle ui-corner-right")
                    .on("mousedown", function () {
                        wasOpen = input.autocomplete("widget").is(":visible");
                    })
                    .on("click", function () {
                        input.trigger("focus");

                        // Close if already visible
                        if (wasOpen) {
                            return;
                        }

                        // Pass empty string as value to search for, displaying all results
                        input.autocomplete("search", "");
                    });
        },
        _source: function (request, response) {
            $(".uploader").addClass('loading');
            var customer = request.term;

            $.ajax({
                url: baseurl + '/fetch_existing_customer',
                data: {"term": request.term},
                cache: true,
                success: function (data) {
                    var main_array = JSON.parse(data);

                    response(main_array['data_array']);
                    $(".uploader").removeClass('loading');
//                             var data_cache=JSON.parse(cache);
//                            setCookie('cache',data_cache,1);
                },
            });

        },
        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                    valueLowerCase = value.toLowerCase(),
                    valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
//        this.input
//          .val( "" )
//          .attr( "title", value + " didn't match any item" )
//          .tooltip( "open" );
//        this.element.val( "" );
//        this._delay(function() {
//          this.input.tooltip( "close" ).attr( "title", "" );
//        }, 2500 );
//            this.input.autocomplete("instance").term = "";
        },
        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });

    $("#existing_customer_name").combobox();
    $("#toggle").on("click", function () {
        $("#combobox").toggle();
    });

});
$(window).load(function () {
//    console.clear();
});




/**
 * Comment
 */
function showProductCategory(el) {

    var cur_product_id = $(el).attr("data-productid");

    console.log(cur_product_id);
    var token = $('#_token').val();
    var url = $('#baseurl2').val();
    $.ajax({
        type: 'get',
        url: 'http://localhost/steel-trading-automation/get_product_type',
        data: {_token: token},
        success: function (data) {
            var main_array = JSON.parse(data);

            var prod = main_array['prod'];

            var str = '<ul class="custom-combobox-toggle ui-corner-right">';
            var str2 = '';
            for (var key in prod) {
                console.log(prod);
                str += '<li id="' + prod[key].id + '"> ' + prod[key].name + ' </li>';
            }

            $('#add_product_name_' + cur_product_id).html(str);
            $('#add_product_name_' + cur_product_id).show();
        }
    });

}



function unitType(parameters) {
    var id = parameters.id.split("_");
    id = id[id.length - 1];

    var i = $('#units_' + id).val();

    if (i == "1")
    {
        $('.kg_list_' + id).show();
        $('.pieces_list_' + id).hide();
        $('.meter_list_' + id).hide();
        $('.ff_list_' + id).hide();
        $('.mm_list_' + id).hide();
        $('#quantity_' + id).val($('#kg_list_' + id).val());
    }
    if (i == "2") {

        $('.kg_list_' + id).hide();
        $('.pieces_list_' + id).show();
        $('.meter_list_' + id).hide();
        $('.ff_list_' + id).hide();
        $('.mm_list_' + id).hide();
        $('#quantity_' + id).val($('#pieces_list_' + id).val());

    }
    if (i == "3")
    {
        $('.kg_list_' + id).hide();
        $('.pieces_list_' + id).hide();
        $('.meter_list_' + id).show();
        $('.ff_list_' + id).hide();
        $('.mm_list_' + id).hide();
        $('#quantity_' + id).val("");

    }

    if (i == "4")
    {
        $('.kg_list_' + id).hide();
        $('.pieces_list_' + id).hide();
        $('.meter_list_' + id).hide();
        $('.ff_list_' + id).show();
        $('.mm_list_' + id).hide();
        $('#quantity_' + id).val($('#ff_list_' + id).val());
    }

    if (i == "5")
    {

        $('.kg_list_' + id).hide();
        $('.pieces_list_' + id).hide();
        $('.meter_list_' + id).hide();
        $('.ff_list_' + id).hide();
        $('.mm_list_' + id).show();
        $('#quantity_' + id).val($('#mm_list_' + id).val());
    }

}

function setQty(parameters) {

    var id = parameters.id.split("_");
    id = id[id.length - 1];
    $('#quantity_' + id).val(parameters.value);
    $('#actual_quantity_' + id).val(parameters.value);

}


$(function () {
    $('#startDate').datepicker({
        changeMonth: false,
        changeYear: false,
        showButtonPanel: false,
        dateFormat: 'MM yy',
        showWeek: true,
//        onClose: function(dateText, inst) {
//            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
//        }
    });
});

//
$(document).on('click', '#export-inventory-list', function (event) {
    $('#export-data-field').val('Export Inventory List');
    $('#export-data-field').attr('value','Export Inventory List');
    $('#filter_search').submit();
});


function under_loading_truck(order_id) {

    var del_spervisor = $("#del_supervisor").val();
    var del_boy = $("#del_boy").val();
    var empty_truck_weight = $("#empty_truck_weight").val();
    var party_name = $("#party_name").val();
    var vehical_number = $("#vehicle_number").val();

    if(del_spervisor || del_boy){
        if(empty_truck_weight){
            $.ajax({
                type: 'POST',
                url: url + '/under_loading_truck',
                data: {
                        party_name: party_name,
                        vehical_number: vehical_number,
                        del_spervisor: del_spervisor,
                        del_boy:del_boy,
                        empty_truck_weight:empty_truck_weight,
                        order_id:order_id,
                        _token: token
                },
                success: function (data) {
                    if(data=='success'){
                        $("#vehicle_number1").val(vehical_number);
                        $("#final_truck_weight").prop('disabled', false);
                        $("#party_name").prop('disabled', false);
                        $("#product_detail_table").prop('disabled', false);
                        $("#labour_pipe").prop('disabled', false);
                        $("#labour_structure").prop('disabled', false);
                        $("#submit_2").prop('disabled', false);

                        $(".err-p").removeClass('text-danger').addClass('text-success').html('Empty truck weight updated successful');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                    else{
                        $(".err-p").removeClass('text-success').addClass('text-danger').html('Please try again..!');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                }
            });
        }
        else{
            $(".err-p").removeClass('text-success').addClass('text-danger').html('Please enter empty truck weight');
            setTimeout(function(){
                $(".err-p").html('');
            }, 5000);
        }
    }
    else{
        $(".err-p").removeClass('text-success').addClass('text-danger').html('Please select delivery supervisor or delivery boy');
        setTimeout(function(){
            $(".err-p").html('');
        }, 5000);

    }
}


function loaded_truck(order_id) {

    var del_spervisor = $("#del_supervisor").val();
    var del_boy = $("#del_boy").val();
    var vehicle_number = $("#vehicle_number").val();

    var final_truck_weight = $("#final_truck_weight").val();
    var product_detail_table = $("#product_detail_table").val();
    var labour_pipe = $("#labour_pipe").val();
    var labour_structure = $("#labour_structure").val();

    if(del_spervisor || del_boy){
        if(final_truck_weight){
            $.ajax({
                type: 'POST',
                url: url + '/loaded_truck',
                data: {
                    labour_structure: labour_structure,
                    labour_pipe: labour_pipe,
                    product_detail_table: product_detail_table,
                    del_spervisor: del_spervisor,
                    del_boy:del_boy,
                    final_truck_weight:final_truck_weight,
                    order_id:order_id,
                    _token: token
                },
                success: function (data) {
                    if(data=='success'){
                        $("#final-submit").prop('disabled',false);
                        $("#vehicle_number1").val(vehicle_number);

                        $(".err-p").removeClass('text-danger').addClass('text-success').html('Final truck weight updated successful');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                    else{
                        $(".err-p").removeClass('text-success').addClass('text-danger').html('Please try again..!');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                }
            });
        }
        else{
            $(".err-p").removeClass('text-success').addClass('text-danger').html('Please enter final truck weight');
            setTimeout(function(){
                $(".err-p").html('');
            }, 5000);
        }
    }
    else{
        $(".err-p").removeClass('text-success').addClass('text-danger').html('Please select delivery supervisor or delivery boy');
        setTimeout(function(){
            $(".err-p").html('');
        }, 5000);

    }
}
function order_assign(){
    var delivery_id = $("#delivery_id").val();
    var del_supervisor =$("#del_supervisor").val(); 
    var token = $('#_token').val();
    if(del_supervisor){
        
         $.ajax({
                type: 'POST',
                url: url + '/order_assign',
                data: {
                    delivery_id:delivery_id,
                    del_supervisor:del_supervisor,
                    _token: token
                },
                success: function (data) {
                    // alert(data);
                    
                    if(data=='success'){
                        $("#final-submit").prop('disabled',false);
                        $(".err-p").removeClass('text-danger').addClass('text-success').html('Order assigned.');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                        window.location.reload();
                    }
                    else{
                        // $(".err-p").removeClass('text-success').addClass('text-danger').html('Please try again..!');
                        $(".err-p").removeClass('text-success').addClass('text-danger').html('Already Assigned');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                }
            });
    }
    else{
            $(".err-p").removeClass('text-success').addClass('text-danger').html('Please Select anyone');
            setTimeout(function(){
                $(".err-p").html('');
            }, 5000);
    }
}


function loaded_assign(){
    var delivery_id = $("#delivery_id").val();
    var assigntype = $("#assign_type").val();
    var del_supervisor =$("#del_supervisor").val(); 
    console.log(del_supervisor);
    var token = $('#_token').val();
    if(del_supervisor){
        
         $.ajax({
                type: 'POST',
                url: url + '/loaded_assign',
                data: {
                    assign_type:assigntype,
                    delivery_id:delivery_id,
                    del_supervisor:del_supervisor,
                    _token: token
                },
                success: function (data) {
                    // alert(data);
                    
                    if(data=='success'){
                        $("#final-submit").prop('disabled',false);
                        $(".err-p").removeClass('text-danger').addClass('text-success').html('Order assigned.');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                        window.location.reload();
                    }
                    else{
                        // $(".err-p").removeClass('text-success').addClass('text-danger').html('Please try again..!');
                        $(".err-p").removeClass('text-success').addClass('text-danger').html('Please select delivery supervisor or delivery boy');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                }
            });
    }
    else{
            $(".err-p").removeClass('text-success').addClass('text-danger').html('Please Select anyone');
            setTimeout(function(){
                $(".err-p").html('');
            }, 5000);
    }
}
function loaded_truck_delivery() {
    var order_id = $("#order_id").val();
    // var del_spervisor = $(".del_supervisor").val();
    // var del_boy = $(".del_boy").val();
    //var vehicle_number = $("#vehicle_number").val();
    var final_truck_weight = $("#final_truck_weight").val();   
    // var product_detail_table = $("#product_detail_table").val();
    // var labour_pipe = $("#labour_pipe").val();
    // var labour_structure = $("#labour_structure").val();

    // if(del_spervisor || del_boy){
        if(final_truck_weight){
            $.ajax({
                type: 'POST',
                url: url + '/loaded_truck_delivery',
                data: {
                    // labour_structure: labour_structure,
                    // labour_pipe: labour_pipe,
                    // product_detail_table: product_detail_table,
                    // del_spervisor: del_spervisor,
                    // del_boy:del_boy,
                    final_truck_weight:final_truck_weight,
                    order_id:order_id,
                    _token: token
                },
                success: function (data) {
                    // alert(data);
                    if(data=='success'){
                        $("#final-submit").prop('disabled',false);
                        // $("#vehicle_number1").val(vehicle_number);

                        $(".err-p").removeClass('text-danger').addClass('text-success').html('Final truck weight updated successful');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                        window.location.reload();
                    }
                    else{
                        // $(".err-p").removeClass('text-success').addClass('text-danger').html('Please try again..!');
                        $(".err-p").removeClass('text-success').addClass('text-danger').html('Please select delivery supervisor or delivery boy');
                        setTimeout(function(){
                            $(".err-p").html('');
                        }, 5000);
                    }
                }
            });
        }
        else{
            $(".err-p").removeClass('text-success').addClass('text-danger').html('Please enter final truck weight');
            setTimeout(function(){
                $(".err-p").html('');
            }, 5000);
        }
    }
//     else{
//         $(".err-p").removeClass('text-success').addClass('text-danger').html('Please select delivery supervisor or delivery boy');
//         setTimeout(function(){
//             $(".err-p").html('');
//         }, 5000);

//     }
// }
function del_super_change(elem) {
  var del_spervisor = $(elem).val();
  var order_id = $(elem).data("order_id");
  var delivery_id = $(elem).data("delivery_id");
  $.ajax({
        type: 'POST',
        url: url + '/delivery_order_spervisor',
        data: {
            del_spervisor: del_spervisor,                        
            order_id:order_id,
            delivery_id:delivery_id,
            _token: token
        },
        success: function (data) {
            // alert(data);
        }
    });
}
function del_boy_change(elem) {
  var del_boy = $(elem).val();
  var order_id = $(elem).data("order_id");
  var delivery_id = $(elem).data("delivery_id");
  $.ajax({
        type: 'POST',
        url: url + '/delivery_order_del_boy',
        data: {
            del_boy: del_boy,                        
            order_id:order_id,
            delivery_id:delivery_id,
            _token: token
        },
        success: function (data) {
            // alert(data);
        }
    });
}
$(document).on("click", "#truck_load", function () {
     var Order_id = $(this).data('order_id');
     var final_truck_weight = $(this).data('final_truck_weight');
     // var product_detail_table = $(this).data('product_detail_table');
     // var labour_pipe = $(this).data('labour_pipe');
     // var labour_structure = $(this).data('labour_structure');
     $(".modal-body #order_id").val( Order_id );
     $(".modal-body #final_truck_weight").val( final_truck_weight );
     // $(".modal-body #product_detail_table").val( product_detail_table );
     // $(".modal-body #labour_pipe").val( labour_pipe );
     // $(".modal-body #labour_structure").val( labour_structure );
});