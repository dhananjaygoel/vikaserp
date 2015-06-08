<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
        DB::table('users')->truncate();
        DB::table('users')->insert(
                array(
                    array('id' => '1','first_name' =>'Super_admin', 'last_name' =>'Super_admin','phone' =>'1234567890','mobile' =>'9898989898', 'email' => 'sadmin@admin.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia','user_type' => '0')
        ));
    }

}
