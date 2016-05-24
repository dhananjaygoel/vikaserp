<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model {

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventory';

    public function product_sub_category() {
        return $this->belongsTo('App\ProductSubCategory', 'product_sub_category_id', 'id');
    }

}
