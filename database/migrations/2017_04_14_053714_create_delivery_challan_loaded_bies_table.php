<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryChallanLoadedBiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('delivery_challan_loaded_bies', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_challan_id')->unsigned()->comment('delivery challan table primary key');
            $table->foreign('delivery_challan_id')->references('id')->on('delivery_challan')->onDelete('cascade');
            $table->integer('loaded_by_id')->unsigned()->comment('loaded bies table primary key');
            $table->foreign('loaded_by_id')->references('id')->on('loaded_bies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('delivery_challan_loaded_bies');
    }

}
