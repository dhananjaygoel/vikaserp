<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AllOrderProducts extends Model {

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'all_order_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','order_type', 'product_category_id', 'unit_id',  'quantity','actual_pieces', 'price','present_shipping', 'remarks'];

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

}
