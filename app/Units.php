<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Units extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['unit_name'];

}
