<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('user_roles', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::drop('user_roles');
    }

}
