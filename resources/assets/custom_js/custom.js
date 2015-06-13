
$(document).ready(function () {
    $("#product_type2").click(function () {
        $(".thick").hide();
    });
    $("#product_type1").click(function () {
        $(".thick").show();

    });

    $('#product_type_select').change(function () {
        var product_type_id = $("#product_type_select").val();
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
   
      $('#loc1').change(function(){
         if($('#loc1').val()=='3'){
           $('.locationtext').toggle();          
        }     
});

});

$(function() {
    $('.smstooltip').tooltip();
});





