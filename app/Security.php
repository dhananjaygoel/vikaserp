<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Security extends Model {

//        use SoftDeletes;
	protected $table = 'security';
        protected $dates = ['deleted_at'];

}
