<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryOrder02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_order', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

            if (!$schema_builder->hasIndex('order_id'))
                $table->index('order_id');
            if (!$schema_builder->hasIndex('customer_id'))
                $table->index('customer_id');
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
        Schema::table('delivery_order', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('order_id'))
                $table->dropIndex('order_id');
            if ($schema_builder->hasIndex('customer_id'))
                $table->dropIndex('customer_id');
            if ($schema_builder->hasIndex('order_status'))
                $table->dropIndex('order_status');
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');


            if ($schema_builder->hasIndex('delivery_order_order_id_index'))
                $table->dropIndex('delivery_order_order_id_index');
            if ($schema_builder->hasIndex('delivery_order_customer_id_index'))
                $table->dropIndex('delivery_order_customer_id_index');
            if ($schema_builder->hasIndex('delivery_order_order_status_index'))
                $table->dropIndex('delivery_order_order_status_index');
            if ($schema_builder->hasIndex('delivery_order_created_at_index'))
                $table->dropIndex('delivery_order_created_at_index');
            if ($schema_builder->hasIndex('delivery_order_updated_at_index'))
                $table->dropIndex('delivery_order_updated_at_index');
        });
    }

}
