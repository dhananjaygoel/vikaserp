<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoadLabour extends Model {

	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'truckdelbys_labour';

    public function labours() {
        return $this->hasMany('App\Labour', 'id', 'labour_id');
    }

}
