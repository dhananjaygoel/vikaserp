<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomers02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('customers', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if (!$schema_builder->hasIndex('owner_name'))
                $table->index('owner_name');
//                DB::statement('ALTER TABLE customers ADD FULLTEXT INDEX owner_name(owner_name)');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('customers', function(Blueprint $table) {

            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
            if ($schema_builder->hasIndex('owner_name'))
                $table->dropIndex('owner_name');

            if ($schema_builder->hasIndex('customers_owner_name_index'))
                $table->dropIndex('customers_owner_name_index');
        });
    }

}
