<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 DB::table('users')->truncate();
        DB::table('users')->insert(
                array(
                    //Super Admin
                    array(
                        'id' => '1',
                        'first_name' => 'Super',
                        'last_name' => 'Admin',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989890',
                        'email' => 'sadmin@admin.com',
                        // 'password' => '$2y$10$vkxXDsOalnfS.ANuYhDIkunjjtZGzvVD497Tvl0/vCnBAMKxkcaYC',
                        'password' => '$2y$10$jpxgWw.w0OgC6yT2/DIycOO/VeeNfxus6FFiZuiglNcPAtirgDTOm',
                        'role_id' => '0'
                    ),
           ));
	}
}