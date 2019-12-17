<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProducts02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_order_products', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

            if (!$schema_builder->hasIndex('order_id'))
                $table->index('order_id');
            if (!$schema_builder->hasIndex('order_type'))
                $table->index('order_type');
            if (!$schema_builder->hasIndex('product_category_id'))
                $table->index('product_category_id');
            if (!$schema_builder->hasIndex('created_at'))
                $table->index('created_at');
            if (!$schema_builder->hasIndex('updated_at'))
                $table->index('updated_at');
            if (!$schema_builder->hasIndex('deleted_at'))
                $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('all_order_products', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('order_id'))
                $table->dropIndex('order_id');
            if ($schema_builder->hasIndex('order_type'))
                $table->dropIndex('order_type');
            if ($schema_builder->hasIndex('product_category_id'))
                $table->dropIndex('product_category_id');
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            if ($schema_builder->hasIndex('deleted_at'))
                $table->dropIndex('deleted_at');

            if ($schema_builder->hasIndex('all_order_products_order_id_index'))
                $table->dropIndex('all_order_products_order_id_index');
            if ($schema_builder->hasIndex('all_order_products_order_type_index'))
                $table->dropIndex('all_order_products_order_type_index');
            if ($schema_builder->hasIndex('all_order_products_product_category_id_index'))
                $table->dropIndex('all_order_products_product_category_id_index');
            if ($schema_builder->hasIndex('all_order_products_created_at_index'))
                $table->dropIndex('all_order_products_created_at_index');
            if ($schema_builder->hasIndex('all_order_products_updated_at_index'))
                $table->dropIndex('all_order_products_updated_at_index');
            if ($schema_builder->hasIndex('all_order_products_deleted_at_index'))
                $table->dropIndex('all_order_products_deleted_at_index');
        });
    }

}
