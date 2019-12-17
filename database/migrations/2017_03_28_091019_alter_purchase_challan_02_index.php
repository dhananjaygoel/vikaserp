<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseChallan02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('purchase_challan', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

            if (!$schema_builder->hasIndex('purchase_order_id'))
                $table->index('purchase_order_id');
            if (!$schema_builder->hasIndex('purchase_advice_id'))
                $table->index('purchase_advice_id');

            if (!$schema_builder->hasIndex('order_status'))
                $table->index('order_status');
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
        Schema::table('purchase_challan', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('purchase_order_id'))
                $table->dropIndex('purchase_order_id');
            if ($schema_builder->hasIndex('purchase_advice_id'))
                $table->dropIndex('purchase_advice_id');

            if ($schema_builder->hasIndex('order_status'))
                $table->dropIndex('order_status');
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');


            if ($schema_builder->hasIndex('purchase_challan_purchase_order_id_index'))
                $table->dropIndex('purchase_challan_purchase_order_id_index');
            if ($schema_builder->hasIndex('purchase_challan_purchase_advice_id_index'))
                $table->dropIndex('purchase_challan_purchase_advice_id_index');

            if ($schema_builder->hasIndex('purchase_challan_order_status_index'))
                $table->dropIndex('purchase_challan_order_status_index');
            if ($schema_builder->hasIndex('purchase_challan_created_at_index'))
                $table->dropIndex('purchase_challan_created_at_index');
            if ($schema_builder->hasIndex('purchase_challan_updated_at_index'))
                $table->dropIndex('purchase_challan_updated_at_index');
        });
    }

}
