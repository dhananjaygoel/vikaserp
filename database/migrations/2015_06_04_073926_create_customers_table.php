<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('customers', function(Blueprint $table) {
            $table->increments('id')->comment('Primary key of the table');
            $table->string('owner_name', 100)->comment('Name of customer');
            $table->string('contact_person', 100)->comment('Contact person name');
            $table->string('company_name', 100)->comment('Name of the company');
            $table->string('address1', 100)->comment('address line 1');
            $table->string('address2', 100)->comment('address line 2');
            $table->string('city', 100)->comment('Name of the city');
            $table->string('state', 100)->comment('State name');
            $table->string('zip', 20)->comment('Zip code');
            $table->string('email', 200)->comment('Email id of the customer');
            $table->string('phone_number1', 20)->comment('Phone number 1');
            $table->string('phone_number2', 20)->comment('Phone number 2');
            $table->string('username', 100)->comment('Username of the customer');
            $table->string('password', 100)->comment('Password of the customer');
            $table->string('tally_name', 100)->comment('tally name');
            $table->string('tally_category', 100)->comment('tally category');
            $table->string('tally_sub_category', 100)->comment('tally sub category');
            $table->string('vat_tin_number', 100)->comment('Vat tin number');
            $table->string('excise_number', 100)->comment('Excise number');
            $table->integer('delivery_location_id')->comment('Delivery location for the customer');
            $table->string('credit_period', 100)->comment('Credit period');
            $table->integer('relationship_manager')->comment('Name of assigned manager');
            $table->enum('customer_status',array('permanent', 'pending'))->comment('If customer is permanent or pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('customers');
    }

}
