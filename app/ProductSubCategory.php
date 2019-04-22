<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ProductSubCategory extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_sub_category';

    protected $fillable = ['product_category_id', 'alias_name', 'size', 'hsn_code', 'unit_id', 'weight', 'thickness', 'standard_length', 'difference', 'length_unit', 'quickbook_item_id'];

    public function product_category() {
        return $this->hasone('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_unit() {
        return $this->hasone('App\Units', 'id', 'unit_id');
    }
    
    public function product_inventory() {
        return $this->hasone('App\Inventory', 'product_sub_category_id', 'id');
    }

    public static $product_sub_category_rules = array(
        'product_category' => 'required',
        'sub_product_name' => 'required',
        'alias_name' => 'required|min:2|max:100',
        'size' => 'required',
        'hsn_code' => 'required|min:2',
        'weight' => 'required',
        'standard_length' => 'required',
        'units' => 'required',
        'difference' => 'required',
        'quickbook_item_id' => 'sometimes',
        'length_unit' => 'sometimes'
    );

}
