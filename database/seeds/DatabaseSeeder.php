<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
//		Model::unguard();
        // $this->call('UserSeeder');
        // $this->call('UserRolesSeeder');
        // $this->call('ProductTypeSeeder');
        // $this->call('UnitsSeeder');
        $this->call('QuickbookToken');
        $this->call('ProductSubCategorySeeder');
    }

}
