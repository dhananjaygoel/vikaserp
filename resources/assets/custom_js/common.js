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
    autoclose: true,

});

$('#performance-months').datepicker({
    format: ' yyyy',
    showButtonPanel: true,
    endDate: new Date(),    
    viewMode: "years", 
    minViewMode: "years",
    autoclose: true,
    startDate: '2001',
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
    
    $(document).on('click', '.delete-receipt', function (event) {
        var receipt_id = $(this).data('id');
        var url = $('#baseurl').attr('name') + "/receipt-master/" + receipt_id;
        $('#delete_receipt_form').attr('action', url);
        $('#delete_receipt_modal').modal('show');
    });
    
    $(document).on('click', '.delete_customer_receipts', function (event) {
        var receipt_id = $(this).data('receipt_id');
        var customer_id = $(this).data('customer_id');
        var url = $('#baseurl').attr('name') + "/receipt-master/delete-customer-receipt/" + receipt_id;
        $('#delete_customer_receipt_form').attr('action', url);
        $('#customer_id').val(customer_id);
        $('#receipt_id').val(receipt_id);
        $('#delete_customer_receipt_modal').modal('show');
        $(this).closest('.st-settle-block').addClass('current_row');
    });
    $(document).on('click', '.submit_customer_receipts_button', function (event) {
        event.preventDefault();
        $('#flash_message_div').remove();
        $('#delete_customer_receipt_modal').modal('hide');
        var receipt_id = $('#delete_customer_receipt_form').find('#receipt_id').val();
        var url = $('#baseurl').attr('name') + "/receipt-master/delete-customer-receipt/" + receipt_id;
        $.ajax({
            url: url,
            type: 'delete',
            dataType: 'JSON',
            data: $('#delete_customer_receipt_form').serialize(),
            success: function (data) {
                if (data.success) {
                    if(data.receipt){
//                        var msg = '<div class="alert alert-success alert-success1">'+
//                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">x</span></button>'+
//                            '<p> Receipt deleted succesfully.</p>'+
//                            '</div>'
//                        $('#table_responsive_msg').prepend(msg);
                        window.location.href = $('#baseurl').attr('name') + "/receipt-master";                        
                    }
                    $('#st-settle-container').find('.current_row').remove();
                }else{
                    var error_msg = '<div class="alert alert-warning" id="flash_message_div">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">x</span></button>'+
                                '<p>'+ data.flash_message +'</p>'+
                            '</div>';
                    $('#edit_receipt').prepend(error_msg);
                    $('#st-settle-container').find('.st-settle-block').removeClass('current_row');
                }
            }
        });

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
     $(document).on('click','#print-inventory-price-list',function(){
         var product_id=$(this).data('id');
         $('.print_inventory_price_list').attr('data-id',product_id);
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
    
    $(document).on('click','.save-unsettled-amount',function(){
        var customer_id = $(this).data('id');
        var new_amount = $(this).closest('td').find('.input-unsettled').val();
        var old_amount = $(this).closest('td').find('.input-unsettled').data('price');       
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/change_unsettled_amount';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                customer_id: customer_id,new_amount:new_amount,old_amount:old_amount
            },
            success: function (data) {
                          
            },
            complete: function () {
            }
        })
    });
    
    $(document).on('click','.settle-payment',function(){
        var due_amount = $(this).data('due_amount');
        var serial_no = $(this).data('serial_no');       
        var challan_id= $(this).data('challan_id');       
        $('#modal_price').attr("value",due_amount);
        $('#modal_price').val(due_amount);
        $('#modal_price').data("price",due_amount);
        $('#modal_price').attr("data-price",due_amount);
        $('#modal-challan').attr("value",challan_id);        
        $('#modal-challan').val(challan_id);                        
        $('#serial-no').html(serial_no);
        $('#amount-error').css('display','none');
        $('#settle_due_modal').modal('show');
    });
    
    $(document).on('change','#collection_territory_select',function(){        
        var teritory_id = $(this).val();
        if(teritory_id==""){
            teritory_id = 0;
        }
        var baseurl = $('#baseurl').attr('name');
        var url = baseurl + '/get_territory_locations';
        $.ajax({
            url: url,
            type: 'get',
            data: {
                teritory_id: teritory_id,
            },
            success: function (data) {                
                $('#assign-territory-location').html(data.html)  
                $('#assign-territory-location').find('#assign_location').multiselect({
                    onInitialized: function (select, container) {
                        $(container).find('.multiselect').addClass('form-control').removeClass('btn btn-default');
                        $(container).find('.multiselect-container').css({'max-height': '200px', 'overflow-y': 'auto'});
                    }
                });
            },
            complete: function () {
            }
        })
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
    
    $(document).on('click','.modal-settle-price',function(event){
        event.preventDefault();
        var entered_price = $('#settle_price_form').find('#modal_price').val();
        var due_amount = $('#settle_price_form').find('#modal_price').data('price');
        if(entered_price>due_amount){
            $('#amount-error').html('Entered amount is greater than Due amount');
            $('#amount-error').css('display','block');
        }
        else if(entered_price==0){            
            $('#amount-error').html('Please Enter valid amount');
            $('#amount-error').css('display','block');
        }else{
            $('#settle_price_form').submit();
        }               
    });
    
    $(document).on('change', '#loaded_by_chart_filter', function () {
        var val = $(this).val();
        var month_val = $('#performance-days').val();
        if (val == "Day") {
            $('#month_div').css('display', 'block');
            $('#year_div').css('display', 'none');
        } else if(val == "Month"){
            month_val = $("#performance-months").val();
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
//        var baseurl = $('#baseurl').attr('name');
//        var url = baseurl + '/performance/loaded-by/loaded-by-performance';
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
                var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"];           
                var date = new Date(data.date);
                var Date_final = monthNames[date.getMonth()] +'-'+date.getFullYear();               
                $('#performance-days').val(Date_final);                
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