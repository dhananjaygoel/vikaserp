<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerManagersTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('customer_managers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('phone_number', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::drop('customer_managers');
    }

}
