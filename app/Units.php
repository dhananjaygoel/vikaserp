<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Units extends Model {

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['unit_name'];
    protected $dates = ['deleted_at'];

}
