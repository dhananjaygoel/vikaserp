<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model {

    protected $table = 'territories';
    protected $fillable = ['teritory_name'];
    protected $dates = ['deleted_at'];
    
    public function territorylocation() {
        return $this->hasMany('App\TerritoryLocation', 'teritory_id', 'id');
    }
}
