<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallan02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

            if (!$schema_builder->hasIndex('order_id'))
                $table->index('order_id');
            if (!$schema_builder->hasIndex('delivery_order_id'))
                $table->index('delivery_order_id');
            if (!$schema_builder->hasIndex('challan_status'))
                $table->index('challan_status');
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
        Schema::table('delivery_challan', function(Blueprint $table) {
             $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('order_id'))
                $table->dropIndex('order_id');
            if ($schema_builder->hasIndex('delivery_order_id'))
                $table->dropIndex('delivery_order_id');            
            if ($schema_builder->hasIndex('challan_status'))
                $table->dropIndex('challan_status');            
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');
            

            if ($schema_builder->hasIndex('delivery_challan_order_id_index'))
                $table->dropIndex('delivery_challan_order_id_index');
            if ($schema_builder->hasIndex('delivery_challan_delivery_order_id_index'))
                $table->dropIndex('delivery_challan_delivery_order_id_index'); 
            if ($schema_builder->hasIndex('delivery_challan_challan_status_index'))
                $table->dropIndex('delivery_challan_challan_status_index'); 
            if ($schema_builder->hasIndex('delivery_challan_created_at_index'))
                $table->dropIndex('delivery_challan_created_at_index');
            if ($schema_builder->hasIndex('delivery_challan_updated_at_index'))
                $table->dropIndex('delivery_challan_updated_at_index');
        });
    }

}
