<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllPurchaseProducts02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_purchase_products', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

            if (!$schema_builder->hasIndex('purchase_order_id'))
                $table->index('purchase_order_id');
            if (!$schema_builder->hasIndex('order_type'))
                $table->index('order_type');
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
        Schema::table('all_purchase_products', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('purchase_order_id'))
                $table->dropIndex('purchase_order_id');
            if ($schema_builder->hasIndex('order_type'))
                $table->dropIndex('order_type');            
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            

            if ($schema_builder->hasIndex('all_purchase_products_purchase_order_id_index'))
                $table->dropIndex('all_purchase_products_purchase_order_id_index');
            if ($schema_builder->hasIndex('all_purchase_products_order_type_index'))
                $table->dropIndex('all_purchase_products_order_type_index'); 
            if ($schema_builder->hasIndex('all_purchase_products_created_at_index'))
                $table->dropIndex('all_purchase_products_created_at_index');
            if ($schema_builder->hasIndex('all_purchase_products_updated_at_index'))
                $table->dropIndex('all_purchase_products_updated_at_index');
            
        });
    }

}
