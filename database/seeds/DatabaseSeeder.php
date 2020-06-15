<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserRolesSeeder::class);
        // $this->call(UnitsSeeder::class);
        // $this->call(ProductTypeSeeder::class);
        $this->call(QuickbookTokenSeeder::class);
        // $this->call(AddAdminUserSeeder::class);
        // $this->call('UpdateDocNum');
        $this->call(UpdateCustomer::class);
    }
}
