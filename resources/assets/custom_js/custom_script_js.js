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
});

