<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductCategory02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('product_category', function(Blueprint $table) {
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
        Schema::table('product_category', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('product_category_created_at_index'))
                $table->dropIndex('product_category_created_at_index');


            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            if ($schema_builder->hasIndex('product_category_updated_at_index'))
                $table->dropIndex('product_category_updated_at_index');
        });
    }

}
