<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('debited_tos')) {
            Schema::create('receipts', function(Blueprint $table) {
                $table->increments('id')->comment('Primary key');
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
            Schema::drop('receipts');
        }
    }

}
