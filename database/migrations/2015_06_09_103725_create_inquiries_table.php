<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiriesTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {

        Schema::create('inquiry', function(Blueprint $table) {
            $table->increments('id')->comment('Primary key');
            $table->integer('customer_id')->comment('Customer id from customers table');
            $table->integer('created_by')->comment('User id from users table');
            $table->integer('delivery_location_id')->comment('Delivery location id from delivery_locations table');
            $table->string('other_location')->comment('Contains other delivery location');
            $table->string('location_difference')->comment('Contains other delivery location difference');
            $table->integer('vat_percentage')->comment('Vat percentage');
            $table->date('expected_delivery_date')->comment('Expected delivery date');
            $table->integer('sms_count')->comment('Track the sms count for an inquiry');
            $table->text('remarks')->comment('Contains remark for the inquiry');
            $table->enum('inquiry_status', array('pending', 'completed', 'canceled'))->comment('Contains inquiry status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {

        Schema::drop('inquiry');
    }

}
