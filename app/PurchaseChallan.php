<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseChallan extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_challan';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_order_id', 'purchase_advice_id', 'bill_number', 'serial_number', 'supplier_id', 'created_by', 'delivery_location_id', 'order_for', 'expected_delivery_date', 'vat_percentage', 'vehicle_number', 'amount', 'unloaded_by', 'labours', 'discount', 'frieght', 'loading_charge', 'grand_total', 'remarks', 'order_status', 'unloading'];

    public function supplier() {
        return $this->hasOne('App\Customer', 'id', 'supplier_id');
    }

    public function challan_loaded_by() {
        return $this->hasMany('App\DeliveryChallanLoadedBy', 'delivery_challan_id', 'id')->where('type','purchase');
//        return $this->belongsTo('App\DeliveryChallanLoadedBy', 'id', 'delivery_challan_id');
//        return $this->hasMany('App\DeliveryChallanLoadedBy', 'id', 'delivery_challan_id');
    }

    public function challan_labours() {
        return $this->hasMany('App\DeliveryChallanLabours', 'delivery_challan_id', 'id')->where('type','purchase');
//      
    }
    
    

    public function purchase_advice() {
        return $this->hasOne('App\PurchaseAdvise', 'id', 'purchase_advice_id')->withTrashed();
        /* Commented by Amit on 22-09-1015 To avoid error for previously deleted purchase advice
          return $this->hasOne('App\PurchaseAdvise', 'id', 'purchase_advice_id');
         */
    }
    
    public function purchase_order() {
        return $this->hasOne('App\PurchaseOrder', 'id', 'purchase_order_id')->withTrashed();
    }

    public function purchase_product() {
        return $this->hasMany('App\PurchaseProducts', 'purchase_order_id', 'id');
    }

    public function orderedby() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function all_purchase_products() {
        return $this->hasMany('App\PurchaseProducts', 'purchase_order_id', 'id')->where('order_type', '=', 'purchase_challan')->where('product_category_id', '>', '0');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function unit() {
        return $this->hasOne('App\Unit', 'id', 'unit_id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id');
    }

}
