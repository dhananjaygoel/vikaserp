<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInquiryProductsTable02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inquiry_products', function(Blueprint $table) {
            $table->integer('app_product_id')->default(0)->after('price')->comment('mobile application local id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inquiry_products', function($table) {
            DB::statement(DB::raw("ALTER TABLE `inquiry_products` ADD `length` VARCHAR(20) NOT NULL;"));
            $table->dropColumn('app_product_id');
        });
    }

}
