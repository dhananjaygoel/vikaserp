<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitedTosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('debited_tos')) {
            Schema::create('debited_tos', function(Blueprint $table) {
                $table->increments('id');
                $table->string('debited_to');
                $table->integer('debited_to_type')->unsigned();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('debited_tos')) {
            Schema::drop('debited_tos');
        }
    }

}
