
$(document).ready(function () {
    $("#product_type2").click(function () {
        $(".thick").hide();
    });
    $("#product_type1").click(function () {
        $(".thick").show();

    });

//    $('#product_sub_category_select').change(function () {
//        
//        var prod = $('#product_sub_category_select').val();
////        alert(prod);
//        if(prod == 1){
//           $('.thick12').css('display','block');
//        }
//        
//        if(prod == 2){
//            $('.thick12').css('display','none');
//        }
//        
//    });
    
    $('#product_sub_category_select').change(function () {
        
        var prod = $('#product_sub_category_select').val();
//        alert(prod);
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

});

$(document).ready(function(){    
    $("#exist_customer").click(function(){
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    
    $("#new_customer").click(function(){
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    
     $("#optionsRadios4").click(function(){
        $(".supplier").show();
       
    });
      $("#optionsRadios3").click(function(){
        $(".supplier").hide();
       
    });
       $("#optionsRadios6").click(function(){
        $(".plusvat").show();
     
    });
      $("#optionsRadios5").click(function(){
        $(".plusvat").hide();
     
    });
});
$('#datepickerDate').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDateComponent').datepicker();
 $('#datepickerDate1').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDateComponent1').datepicker();

$(document).ready(function(){
    $("#addmore1").click(function(){
        $(".row5").hide();
        $(".row6").show();
        $(".row7").show();
    });
    $("#addmore2").click(function(){
        $(".row7").hide();
        $(".row8").show();
        $(".row9").show();
    });
    $("#addmore3").click(function(){
        $(".row9").hide();
        $(".row10").show();
        $(".row11").show();
    });
      $("#addmore4").click(function(){
        $(".row11").hide();
        $(".row12").show();
        
    });
   
      $('#order_location').change(function(){

         if($('#order_location').val() =='-2'){
                $('.locationtext').show();    
        }else{
            $('.locationtext').hide(); 
        }     
});

});

$(function() {
    $('.smstooltip').tooltip();
});

$('#add_more_product').click(function(){
    
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
        
        var str = ' <tr id="add_row_' + current_row_count + '" class="add_product_row">'+
                '    <td>'+
               '<div class="form-group searchproduct">' +
                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
                '<i class="fa fa-search search-icon"></i>' +
                '</div>' +
                '    </td>'+
                '    <td>'+
                '        <div class="form-group">'+
                '            <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">'+
                '        </div>'+
                '    </td>'+
                '    <td>'+
                '        <div class="form-group ">'+
                '           <select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
                '               <option value="" selected="">Unit</option>' +
                '           </select>' +
                '        </div>'+
                '    </td>  '+
                '    <td>  '+
                '        <div class="form-group">'+
                '            <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">'+
                '        </div>'+
                '    </td>'+
                '    <td class="shippingcolumn">'+
                '        <div class="row ">'+
                '            <div class="form-group col-md-12">'+
                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
                '            </div>'+
                '        </div>'+
                '    </td>'+
                '    <td>   '+
                '        <div class="form-group">'+
                '            <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">'+
                '        </div>'+
                '    </td>'+
                '</tr>';       

        $("#table-example").children("tbody").append(str);
    
//    var html = '<tr id="add_row_' + current_row_count + '" class="add_product_row">' +
//                '<td class="col-md-3">' +
//                '<div class="form-group searchproduct">' +
//                '<input class="form-control" placeholder="Enter product name " type="text" name="product[' + current_row_count + '][name]" id="add_product_name_' + current_row_count + '" onfocus="product_autocomplete(' + current_row_count + ');">' +
//                '<input type="hidden" name="product[' + current_row_count + '][id]" id="add_product_id_' + current_row_count + '">' +
//                '<i class="fa fa-search search-icon"></i>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group ">' +
//                '<select class="form-control" name="product[' + current_row_count + '][units]" id="units_' + current_row_count + '">' +
//                '<option value="" selected="">Unit</option>' +
//                '</select>' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-1">' +
//                '<div class="form-group">' +
//                '<input id="quantity_' + current_row_count + '" class="form-control" placeholder="Present shipping" name="product[' + current_row_count + '][present_shipping]" value="" type="text">' +
//                '</div>' +
//                '</td>' +
//                '<td class="col-md-2">' +
//                '<div class="form-group">' +
//                '<input type="text" class="form-control" placeholder="price" id="product_price_' + current_row_count + '" name="product[' + current_row_count + '][price]">' +
//                '</div>' +
//                '</td><td></td>' +
//                '<td class="col-md-4">' +
//                '<div class="form-group">' +
//                '<input id="remark" class="form-control" placeholder="Remark" name="product[' + current_row_count + '][remark]" value="" type="text">' +
//                '</div>' +
//                '</td>' +
//                '</tr>';
//       
    
    
    
    
    
    
    
    
        
        
});





