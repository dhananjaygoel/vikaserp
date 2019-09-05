<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
       $categories = App\ProductCategory::all();
       foreach ($categories as $cat) {
        print_r($cat);
       }

    }

}
