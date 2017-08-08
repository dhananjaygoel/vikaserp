<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseOrder01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('purchase_order', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if (!$schema_builder->hasIndex('order_status'))
                $table->index('order_status');
            
            if (!$schema_builder->hasIndex('supplier_id'))
                $table->index('supplier_id');

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
        Schema::table('purchase_order', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
            if ($schema_builder->hasIndex('order_status'))
                $table->dropIndex('order_status');
            if ($schema_builder->hasIndex('purchase_order_order_status_index'))
                $table->dropIndex('purchase_order_order_status_index');
            
            if ($schema_builder->hasIndex('supplier_id'))
                $table->dropIndex('supplier_id');
            if ($schema_builder->hasIndex('purchase_order_supplier_id_index'))
                $table->dropIndex('purchase_order_supplier_id_index');

            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('purchase_order_created_at_index'))
                $table->dropIndex('purchase_order_created_at_index');


            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            if ($schema_builder->hasIndex('purchase_order_updated_at_index'))
                $table->dropIndex('purchase_order_updated_at_index');
            
            if ($schema_builder->hasIndex('deleted_at'))
                $table->dropIndex('deleted_at');
            if ($schema_builder->hasIndex('purchase_order_deleted_at_index'))
                $table->dropIndex('purchase_order_deleted_at_index');
        });
    }

}
