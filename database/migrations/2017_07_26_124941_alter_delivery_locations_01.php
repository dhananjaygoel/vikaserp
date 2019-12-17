<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryLocations01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_locations', function(Blueprint $table) {
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
        Schema::table('delivery_locations', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('delivery_locations_created_at_index'))
                $table->dropIndex('delivery_locations_created_at_index');


            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            if ($schema_builder->hasIndex('delivery_locations_updated_at_index'))
                $table->dropIndex('delivery_locations_updated_at_index');
        });
    }

}
