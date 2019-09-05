<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
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
                        'email' => 'saranyanuraj@gmail.com',
                        'password' => '$2y$10$jpxgWw.w0OgC6yT2/DIycOO/VeeNfxus6FFiZuiglNcPAtirgDTOm',
                        'role_id' => '0'
                    ),
        ));
    }

}
