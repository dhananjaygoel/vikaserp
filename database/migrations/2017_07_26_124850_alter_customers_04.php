<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomers04 extends Migration {

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


            if (!$schema_builder->hasIndex('created_at'))
                $table->index('created_at');
            
            if (!$schema_builder->hasIndex('updated_at'))
                $table->index('updated_at');
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
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('customers_created_at_index'))
                $table->dropIndex('customers_created_at_index');
            
            
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            if ($schema_builder->hasIndex('customers_updated_at_index'))
                $table->dropIndex('customers_updated_at_index');
        });
    }

}
