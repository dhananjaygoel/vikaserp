<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickbookToken extends Model {

	protected $table = "quickbook_token";
    protected $fillable = ['client','secret','access_token','refresh_token'];
}
