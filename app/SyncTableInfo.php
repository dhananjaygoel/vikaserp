<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyncTableInfo extends Model {
    use SoftDeletes;
    protected $table = 'sync_table_infos';
    protected $dates = ['deleted_at'];
    protected $hidden = ['updated_at', 'created_at','deleted_at'];
	//

}
