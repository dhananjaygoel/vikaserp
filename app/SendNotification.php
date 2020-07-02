<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SendNotification extends Model {

	/**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['msg','assigned_by','assigned_to', 'status'];
	protected $dates = ['deleted_at'];



}
