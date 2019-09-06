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
            /* DB::table('quickbook_token')->where('id', 4)->update([
               'client' => 'ABpdVkDFhsmsp1KNFoDuYhgAATppzXoDlw9FFa7nE2PG9hmQZv',
                'secret' => '3lnaubZB1MIo69RmH6geLezsPJM9aD99I8HsahXK',
                'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..i-oNJXc3VFt-3oPWVcWgOQ.kQgABF0cnhe7ZC6B3BbEfYIco33gcwMofBYlx70-ppi09QK24i4OEeqltc6WvNDKf9__NInhOC13J2HF85Q3PXvGztkRjdoa-t8gV7FD2j2kbv24Z2Un2FXye1PkYY4brWBmypcyS5oi1lUP7c1DCZu1nr_NAPJ05mFWbvpdzxJj9-pJrmwHuFJbgXUWjNeUKhDB4E1PiihxYf5kLONlVZ_J0VvoNnNRKyb5r-RO6GGp5wj4vbS5EPEMmfCzCynE2NVFrb5TjMdX7nBTk2FMuYQZeA146gXoaJVqJI-weK16gsGOr7zntn-11vn7DRxttRsREjYVKr2Jk5ive3kSukioc2kVTodRj08KgOlzjh9e3mRkCzsGT8Dy8HJGrmp2wQquh03T-Ah8OihBhRWayGVx3Xkp8Wxe8Q5Bk6pMv12k9nKARbvM6NYNXF6JIFMb3fpjPiqD-kwHUfykqHDcR1S3QauGt0M-zBAkBTt331Bmh_e8jAtKCXOGjy_k4QjeV9OuL7VowQhKOozkA8xlfsJ-BE_H9xLsZWJFHBbn_HZxViBLBkgO9eg8fKPo4fZr5nD9o5y7R8qPRAcmcwEbvx0JcFB9vi-hHcunff3k30YYZsiqhl0B4yzOiS13HjUoaa9kJEjB5FoU7wbxor4w94czCtPjYVftKtb185q_wHikls2JyipmE0LCvtVEHsxCUbVC3jdOMmJf3TtIWc-ygZav5dFsJ8hBvSxndOsDhSY.2SgY2wzDgKRsJWhYaKAfRA',
                'refresh_token' => 'AB11576409863t9pNqU3xQhqsozUrD3m4WefjSHrTpzGnUBZ1K'
            ]);*/
            /*DB::table('unit')->insert(
                array(
                    array(
                        'id' => '4',
                        'unit_name' => 'ft'
                    ),
                    array(
                        'id' => '5',
                        'unit_name' => 'mm'
                    ),
                   
        ));         */

        DB::table('users')->insert(
                array(
                    //Super Admin
                    array(
                        'id' => '39',
                        'first_name' => 'Saranya',
                        'last_name' => 'Admin',
                        'phone_number' => '9898989815',
                        'mobile_number' => '9898989815',
                        'email' => 'abhi@vikaserp.in',
                        // 'password' => '$2y$10$vkxXDsOalnfS.ANuYhDIkunjjtZGzvVD497Tvl0/vCnBAMKxkcaYC',
                        'password' => '$2y$10$wraJ4uNWGMrftZnjpQcnuu.ar8OQQGntL2Hgol/eFT4Vp6V34Oaay',
                        'role_id' => '0'
                    )
                   
        ));
       }

    

}
