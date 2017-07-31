<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsers01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if (!$schema_builder->hasIndex('email'))
                $table->index('email');
            
            if (!$schema_builder->hasIndex('mobile_number'))
                $table->index('mobile_number');

            if (!$schema_builder->hasIndex('password'))
                $table->index('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
            if ($schema_builder->hasIndex('email'))
                $table->dropIndex('email');
            if ($schema_builder->hasIndex('users_email_index'))
                $table->dropIndex('users_email_index');
            
            if ($schema_builder->hasIndex('mobile_number'))
                $table->dropIndex('mobile_number');
            if ($schema_builder->hasIndex('users_mobile_number_index'))
                $table->dropIndex('users_mobile_number_index');


            if ($schema_builder->hasIndex('password'))
                $table->dropIndex('password');
            if ($schema_builder->hasIndex('users_password_index'))
                $table->dropIndex('users_password_index');
        });
    }

}
