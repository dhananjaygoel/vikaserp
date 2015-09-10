<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ProductCategory extends Model implements AuthenticatableContract, CanResetPasswordContract {

use Authenticatable,
 CanResetPassword;

/**
 * The database table used by the model.
 *
 * @var string
 */
protected $table = 'product_category';

public function product_sub_category() {
return $this->hasone('App\ProductSubCategory', 'product_category_id', 'id');
}

public function product_sub_categories() {
return $this->hasMany('App\ProductSubCategory', 'product_category_id', 'id');
}

public function product_type() {
return $this->hasone('App\ProductType', 'id', 'product_type_id');
}

}

}
