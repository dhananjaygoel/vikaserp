$(document).ready(function () {
    var current_url = window.location.href;
  
    var split_url = current_url.split("/");
    if (split_url[3] == 'delivery_order' && split_url[5] == 'edit') {
        $('#add_product_row_delivery_order').trigger('click');
    }
    if (split_url[3] == 'orders' && split_url[5] == 'edit') {
        $('#add_product_row').trigger('click');
    }
    if (split_url[3] == 'inquiry' && split_url[5] == 'edit') {
        $('#add_product_row').trigger('click');
    }
    if (split_url[3] == 'create_delivery_order') {
        $('#add_product_row_delivery_order').trigger('click');
    }
    if (split_url[3] == 'place_order') {
        $('#add_product_row').trigger('click');
    }
    if (split_url[4] == 'delivery_order' && split_url[6] == 'edit') {
        $('#add_product_row_delivery_order').trigger('click');
        
    }
    if (split_url[4] == 'orders' && split_url[6] == 'edit') {
        $('#add_product_row').trigger('click');
    }
    if (split_url[4] == 'inquiry' && split_url[6] == 'edit') {
        $('#add_product_row').trigger('click');
    }
    if (split_url[4] == 'create_delivery_order') {
        $('#add_product_row_delivery_order').trigger('click');
    }
    if (split_url[4] == 'place_order') {
        $('#add_product_row').trigger('click');
    }
    if (split_url[3] == 'purchase_orders' && split_url[5] == 'edit') {
//        $('#add_purchase_product_row').trigger('click');
        jQuery('#add_purchase_product_row')[0].click();
    }
    if (split_url[4] == 'purchase_orders' && split_url[6] == 'edit') {
//        $('#add_purchase_product_row').trigger('click');
        jQuery('#add_purchase_product_row')[0].click();
    }
    if (split_url[3] == 'create_purchase_advice') {
        jQuery('#add_purchase_advice_product_row')[0].click();
    }
    if (split_url[4] == 'create_purchase_advice') {
        jQuery('#add_purchase_advice_product_row')[0].click();
    }
    if (split_url[3] == 'purchaseorder_advise' && split_url[5] == 'edit') {
//        $('#add_purchase_product_row').trigger('click');
        jQuery('#add_purchase_advice_product_row')[0].click();
    }
    if (split_url[4] == 'purchaseorder_advise' && split_url[6] == 'edit') {
//        $('#add_purchase_product_row').trigger('click');
        jQuery('#add_purchase_advice_product_row')[0].click();
    }

   
   
});
