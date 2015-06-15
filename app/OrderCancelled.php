<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCancelled extends Model {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','reason_type','reason','cancelled_by'];

   
}
