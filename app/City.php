<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model {

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'city';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['city_name', 'state_id'];
    protected $dates = ['deleted_at'];

    public function states() {
        return $this->hasOne('App\States', 'id', 'state_id');
    }

}
