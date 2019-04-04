<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hsn extends Model {
    use SoftDeletes;
    protected $table = "hsn";
}
