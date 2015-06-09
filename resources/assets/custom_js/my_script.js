$(document).ready(function(){
    $("#checkbox-inl-1").click(function(){
       
        $(".category_div").toggle("slow");
        
    });
});

$('.deleteCustomer').click(function (){
    $(this).parents('.modal').find('form').submit();
});