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

    public function product_category() {
        return $this->hasone('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_categories()
    {
        return $this->hasone('App\ProductCategory', 'id','product_type_id');
    }
    public function product_unit() {
        return $this->hasone('App\Units', 'id', 'unit_id');
    }

    public static $product_sub_category_rules = array(
        'product_type' => 'required',
        'select_product_categroy' => 'required',
        'alias_name' => 'required|min:2|max:100',
        'size' => 'required',
        'weight' => 'required',
        'standard_length' => 'required',
        'units' => 'required',
        'difference' => 'required'
    );

}
