<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('supplier', function(Blueprint $table) {
            $table->increments('id');
            $table->string('supplier_name');
            $table->string('mobile');
            $table->string('credit_period');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::drop('supplier');
    }

}
