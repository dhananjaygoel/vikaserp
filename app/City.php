<?php

namespace App;

use App\States;
use Illuminate\Database\Eloquent\Model;

class City extends Model {

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

    public function states() {
        return $this->hasOne('App\States', 'id', 'state_id');
    }

}
