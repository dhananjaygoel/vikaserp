
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
                    str += '<option value="'+ prod[key].id +'"> '+ prod[key].product_category_name +' </option>';
                }
                
                $('#select_product_categroy').html(str);
            }
        });
    });  
    
});





