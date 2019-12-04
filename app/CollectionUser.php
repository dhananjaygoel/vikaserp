<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
class CollectionUser extends Model {

    protected $table = 'collection_user_location';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    protected $newuser_rules = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|max:100',
        'password_confirmation' => 'required|min:6|max:100|same:password',
        'mobile_number' => 'integer|digits_between:10,15|required|unique:users',
        'location' => 'required|array|min:1',
        'territory' => 'required'
    );
    
    public static $update_password = array(
        'password' => 'required|min:8|max:20|confirmed ',
        'password_confirmation' => 'required'
    );

	protected $validator;

    public function user_validate($data)  
    {
	    $v = Validator::make($data, $this->newuser_rules);
	    if ($v->fails())
	    {
	    	$this->validator = $v;
	        return false;
	    }else {
	    	return true;
	    }  
	}
	public function getvalidatorobj()
    {
        return $this->validator;
    }

    public function location_data() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'location_id');
    }

    public function collection_user() {
        return $this->hasMany('App\User', 'id', 'user_id');
    }
}
