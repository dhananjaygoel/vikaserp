/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('#performance-days').datepicker({
    format: 'MM-yyyy',
    showButtonPanel: true,
    endDate: new Date(),
    viewMode: "months", 
    minViewMode: "months",
    autoclose: true

});

$('#performance-months').datepicker({
    format: ' yyyy',
    showButtonPanel: true,
    endDate: new Date(),    
    viewMode: "years", 
    minViewMode: "years",
    autoclose: true,
    startDate: '2001'
});


$(document).ready(function () {
    
    $('#loaded_by_select,#multi-territory-location ,#labour_select').multiselect({
        nonSelectedText: 'Please Select',
        includeSelectAllOption: true,
        enableFiltering: true,
        buttonWidth: '400px'
    });
    
    $(document).on('click', '.delete-loader', function () {
        var baseurl = $('#baseurl').attr('name');
        $('#delete_loaded_by_form').attr('action', baseurl + '/performance/loaded-by/' + $(this).data('id'));
        $('#delete_loaded_by_modal').modal('show');
    });    

    $(document).on('click', '.delete-territory', function (event) {
        var territory_id = $(this).data('id');
        var url = $('#baseurl').attr('name') + "/territory/" + territory_id;
        $('#delete_teritory_form').attr('action', url);
        $('#delete_location_modal').modal('show');
    });

//    $.validator.addMethod("noSpace", function (value, element) {
//        return $.trim(value) != "";
//    }, "This field is required");
//    $("form[id='add_loaded_by']").validate({
//        rules: {
//            "first_name": {required: true, noSpace: true, minlength: 2, maxlength: 100},
//            "last_name": {minlength: 2, maxlength: 100},
//            "mobile_number": {required: true, noSpace: true, minlength: 10, maxlength: 10, number: true},
//            "password": {required: true, noSpace: true, minlength: 6, maxlength: 10},
//            "confirm_password": {required: true, noSpace: true, minlength: 6, maxlength: 10, equalTo: "#password"},
//        },
//        messages: {
//            "first_name": {required: "Please enter the first name", minlength: "Minimum 2 characters required", maxlength: "First name should not be more than 100 characters"},
//            "last_name": {required: "Please enter the last name", minlength: "Minimum 2 characters required", maxlength: "Last name should not be more than 100 characters"},
//            "mobile_number": {required: "Please enter the mobile number", number: "MObile number must in digits"},
//        },
//        errorPlacement: function (error, element) {
//            error.insertAfter(element);
//        },
//        submitHandler: function (form) {
//            common_form_submit(form);
//        }
//    });
//    $("form[id='edit_loaded_by']").validate({
//        rules: {
//            "first_name": {required: true, noSpace: true, minlength: 2, maxlength: 100},
//            "last_name": {minlength: 2, maxlength: 100},
//            "mobile_number": {required: true, noSpace: true, minlength: 10, maxlength: 10, number: true},
//            "password": {required: function (element) {
//                    return $("#confirm_password").val() != "";
//                }, noSpace: true, minlength: 6, maxlength: 10},
//            "confirm_password": {required: function (element) {
//                    return $("#password").val() != "";
//                }, noSpace: true, minlength: 6, maxlength: 10, equalTo: "#password"},
//        },
//        messages: {
//            "first_name": {required: "Please enter the first name", minlength: "Minimum 2 characters required", maxlength: "First name should not be more than 10 characters"},
//            "last_name": {minlength: "Minimum 2 characters required", maxlength: "Last name should not be more than 10 characters"},
//            "mobile_number": {required: "Please enter the mobile number", number: "MObile number must in digits"},
//        },
//        errorPlacement: function (error, element) {
//            error.insertAfter(element);
//        },
//        submitHandler: function (form) {
//            common_form_submit(form);
//        }
//    });

    $(document).on('change', '#inventory_report_filter', function () {
        var product_id = $(this).val();
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/get_inventory_report';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                product_id: product_id,
            },
            success: function (data) {
                $('#export_product_id').val(product_id);
                $('#print-inventory-report').data("id",product_id);
                $('#print-inventory-report').attr("data-id",product_id);
                $('.report-table-content').html(data.html)                
            },
            complete: function () {
            }
        })
    });

    $(document).on('change', '#inventory_price_list_filter', function () {
        var product_id = $(this).val();
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/get_inventory_price_list';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                product_id: product_id,
            },
            success: function (data) {
                $('#export_product_id').val(product_id);
                $('#print-inventory-price-list').data("id",product_id);
                $('#print-inventory-price-list').attr("data-id",product_id);
                $('.report-table-content').html(data.html)                
            },
            complete: function () {
            }
        })
    });
     $(document).on('click','#print-inventory-report',function(){
         var product_id=$(this).data('id');
         $('.print_inventory_report_list').attr('data-id',product_id);
     });
    
    $(document).on('click','.inventory-price-value',function(){
        var product_id = $(this).data('product');
        var thickness = $(this).data('thickness');
        var size = $(this).data('size');
        var price = $(this).val();
        $('#modal_price').attr("value",price);        
        $('#modal_price').val(price);                
        $('#modal_price').data("id",product_id);
        $('#modal_price').attr("data-id",product_id);
        $('#prod-thickness').val(thickness);
        $('#prod-size').val(size);
        $('#price_list_modal').modal('show');
    });
    
    $(document).on('click','.modal-price-save',function(){
        var new_price = $('#inventory_price_form').find('#modal_price').val();
        var product_id = $('#inventory_price_form').find('#modal_price').data('id');
        var thickness = $('#inventory_price_form').find('#prod-thickness').val();
        var size = $('#inventory_price_form').find('#prod-size').val();
        var url = baseurl+'/set_inventory_price';
        $.ajax({
             url: url,
             type: 'get',
             data: { product_id: product_id,size: size,thickness:thickness,new_price:new_price},
             success: function(data) {
                 $('.inventory-price-value[data-thickness="'+ thickness +'"][data-size="'+ size +'"]').val(new_price);
             },
             complete: function() {}
        })
    });
    
    $(document).on('change', '#loaded_by_chart_filter', function () {
        var val = $(this).val();
        var month_val = $('#performance-days').val();
        if (val == "Day") {
            $('#month_div').css('display', 'block');
            $('#year_div').css('display', 'none');
        } else if(val == "Month"){
            month_val = "all";
            $('#month_div').css('display', 'none');
            $('#year_div').css('display', 'block');
        }
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/performance/loaded-by/loaded-by-performance';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                val: val,
                month: month_val
            },
            success: function (data) {
                $('.report_table').html(data.html)
            },
            complete: function () {
            }
        })
    });
    $(document).on('submit', '.loaded_by_performance_search_form', function () {
        var val = $('#loaded_by_chart_filter').val();
        if (val == "Day") {
            var month_val = $('#performance-days').val();
        } else if(val == "Month"){
            var month_val = $('#performance-months').val();
        }
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/performance/loaded-by/loaded-by-performance';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                val: val,
                month: month_val
            },
            success: function (data) {
                $('.report_table').html(data.html)
            },
            complete: function () {
            }
        })
    });
});




$(document).on('change', '#labour_chart_filter', function () {
        var val = $(this).val();
        var month_val = $('#performance-days').val();
        if (val == "Day") {
            $('.day-wise').css('display', 'block');
            $('.month-wise').css('display', 'none');
        } else if(val == "Month"){
            month_val = "all";
            $('.day-wise').css('display', 'none');
            $('.month-wise').css('display', 'block');
        }
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/performance/loaded-by/loaded-by-performance';
//        $.ajax({
//            url: url,
//            type: 'get',
//            data: {
//                val: val,
//                month: month_val
//            },
//            success: function (data) {
//                $('.report_table').html(data.html)
//            },
//            complete: function () {
//            }
//        })
    });
    
    
    $(document).on('submit', '.labours_performance_search_form', function () {
       
        var val = $('#labour_chart_filter').val();
        if (val == "Day") {
            var month_val = $('#performance-days').val();
        } else if(val == "Month"){
            var month_val = $('#performance-months').val();
        }
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/performance/labours/labour-performance';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                val: val,
                month: month_val
            },
            success: function (data) {
                $('.report_table').html(data.html)
            },
            complete: function () {
            }
        })
    });

function common_form_submit(form) {
    var url = $(form).attr('action');
    var submit_text = $(form).find('input[type="submit"]').val();
    var submit_type = $(form).attr('method');
    $(form).find('input[type="submit"]').val('Please wait..').prop('disabled', 'disabled');
    if ($(form).validate()) {
        $.ajax({
            url: url,
            data: new FormData(form),
            type: submit_type,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                if (data.status === '200') {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                }
                if (data.status === '400') {
//                    if (data.redirect_url) {
//                       // window.location.href = data.redirect_url;
//                    }
                    if (data.error) {
                        var validator = $(form).validate();
                        validator.showErrors(data.error);
                    }
//                    if (data.error_t) {
//                        $('#error_container').html('<div class="alert alert-danger get-style-error">'+data.error+'</div>');
//                        $(window).scrollTop($('#error_container').offset().top);
//                    }
                }
            },
            error: function () {
            },
            complete: function () {
                $(form).find('input[type="submit"]').val(submit_text).prop('disabled', false);
            }
        });
    } else {
        return false;
    }
}