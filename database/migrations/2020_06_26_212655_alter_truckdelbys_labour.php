<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTruckdelbysLabour extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('truckdelbys_labour', function ($table) {
            $table->integer('truck_weight_id')->after('del_boy_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('truckdelbys_labour', function ($table) {
            $table->dropColumn('truck_weight_id');
        });
    }
}
