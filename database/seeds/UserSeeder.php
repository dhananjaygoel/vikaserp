<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
       /*$subcategories = App\ProductSubCategory::all();
       foreach ($subcategories as $subcat) {
        $hsn = App\ProductCategory::where('id', $subcat->product_category_id)->get()->first();
         DB::table('product_sub_category')->where('product_category_id', $subcat->product_category_id)->update([
                'hsn_code' => $hsn->hsn_code,
            ]);*/
             DB::table('quickbook_token')->where('id', 3)->update([
               'client' => 'ABB6G2Da7EJLem4XfeXEn6vgdisRSVijSzdWNRKExRHVwssNVH',
                'secret' => 'dUOLrdPi7J0hNzndtc7uUEconleZa9BeuoknEFay',
                'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..mmU0boY3SXIL4msPklHB7A.9ZaHLBgAVku1cyjwhQ83SNKfUTN0Opr4gbnSFyNrXL0zoYCz5ZJKLrwzpuKpx01uQJYt8wpJUFrRHyfzULkZCcYWuTfpvYXW1wy_6drJS6_g9ay0l1pHn8Qdsnc4bsktVd-Q_k66ANnYqdjGDd5YlEcGV9o4zA68uTkc8CGCCb6geJxx_6-mdWSXkRuH_S6XGk4RfcqCaRbPz-FMVUQFqbZdNWS7-bXipONzSslkaSXZZwGr2ywlYNqyX05_Ly9yoA5Rkw2TLW6c3eHX6vYCqKAhwOqnAeUaRlD0nldr1Xm0fZ6_z2ZAg_AIG6XdtmfafmwUhQQpzNDQF1469NLCUn2jCVgInn7oL-TT3SBMnQZVgEuwos2_lHf1YSkOv3Ofu2MFJJofZ0E2BhCnzTED7PkEBdlC0Q5ys3oiLjMZKYXrOgtQhbWLNYu904Na1qKlclQf8vazK6um4RTtw90Pzw8UhsJA8O3Z-xUqI_cmxKln6xtW4H_5E3Gb16kUwIgM2ygBl_H67SRi1X1rYmc_ve_2reTR4UNV_2rzS88iE3_UmbrBZTF3ADutfJEP38JrMVOXgGFKvPHxKvNXy6zhq1-k7hzmjr73W5PVdOW1FLuP9Djx1dkcjGu9mnJkoWzdJ5ALcAlmfEED4c39P8f8eVHLXbAxv4baKx0nOcr2M1kL96YATEZU94_SzPG0qnapG9NxnAOcKxXYd20WkThh9j8pqaXVN3vhDh9yhgLg1pQ.wqtSret8Jsh_LWfNpzX7jw',
                'refresh_token' => 'AB11576409560TS6p25yWjyjV73FSzdBqB4gaGXj4uci1dYru7'
            ]);
       }

    

}
