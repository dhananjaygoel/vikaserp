<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
//		Model::unguard();
                $this->call('UserSeeder');
                $this->call('UserRoleSeeder');
                $this->call('ProductsSeeder');
		// $this->call('UserTableSeeder');
	}

}

class UserSeeder extends Seeder {

    public function run() {
        DB::table('users')->truncate();
        DB::table('users')->insert(
                array(
                    array('id' => '1','first_name' =>'Super_admin', 'last_name' =>'Super_admin','phone_number' =>'1234567890','mobile_number' =>'9898989890', 'email' => 'sadmin@admin.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia','role_id' => '0'),
                    array('id' => '2','first_name' =>'Admin_firstname', 'last_name' =>'Admin_last_name','phone_number' =>'1234567890','mobile_number' =>'9898989891', 'email' => 'admin@admin.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia','role_id' => '1'),
                    array('id' => '3','first_name' =>'sales_staff_first_name', 'last_name' =>'sales_staff_last_name','phone_number' =>'1234567890','mobile_number' =>'989898993', 'email' => 's1@s1.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia','role_id' => '2'),
                    array('id' => '4','first_name' =>'delivery_staff_first_name', 'last_name' =>'delivery_staff_last_name','phone_number' =>'1234567890','mobile_number' =>'989898994', 'email' => 'd1@d1.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia','role_id' => '3')
                ));
    }

}
class ProductsSeeder extends Seeder {

    public function run() {
        DB::table('product_type')->truncate();
        DB::table('product_type')->insert(
                array(
                    array('id' => '1','name' =>'Pipe' ),
                    array('id' => '2','name' =>'structure')                    
                ));
    }

}
class UserRoleSeeder extends Seeder {

    public function run() {
        DB::table('user_roles')->truncate();
        DB::table('user_roles')->insert(
                array(
                    array('id' => '1','role_id' => '0','name' =>'Super Admin'),
                    array('id' => '2','role_id' => '1','name' =>'Admin'),
                    array('id' => '3','role_id' => '2','name' =>'Sales Staff'),
                    array('id' => '4','role_id' => '3','name' =>'Delivery Staff'),                    
                ));
    }

}
