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
        $this->call('UserRolesSeeder');
        $this->call('UnitsSeeder');
        $this->call(QuickbookTokenSeeder::class);
        $this->call('UpdateDocNum');
    }
}
