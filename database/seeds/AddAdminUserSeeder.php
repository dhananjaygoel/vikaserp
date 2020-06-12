<?php

use Illuminate\Database\Seeder;

class AddAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            array(
                'first_name'=> 'Alphalogic',
                'last_name' => 'Techsys',
                'role_id' => 0,
                'mobile_number' => '9898989898',
                'email' => 'test@alphalogic.com',
                'password' => '$2y$10$GWTfwLg8SdmdKrIvnW8mEeSyKk5wd2LyXgnFCFic3WGIB7M7cJapy'
            )
        );
    }
}
