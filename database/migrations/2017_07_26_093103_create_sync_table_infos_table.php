<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyncTableInfosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sync_table_infos', function(Blueprint $table) {
            $table->increments('id');
            $table->string('table_name')->index('table_name');
            $table->timestamp('sync_date')->index('sync_date')->default('0000-00-00 00:00:00');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('sync_table_infos');
    }

}
