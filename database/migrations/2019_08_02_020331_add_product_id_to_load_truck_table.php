<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddProductIdToLoadTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('load_truck', function (Blueprint $table) {

            // 1. Create new column
            // You probably want to make the new column nullable
            $table->binary('product_id')->nullable()->after('userid');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('load_truck', function (Blueprint $table) {

            $table->dropColumn('product_id');
        });
    }
}