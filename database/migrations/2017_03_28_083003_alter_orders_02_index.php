<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrders02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('orders', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

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
        Schema::table('orders', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('order_status'))
                $table->dropIndex('order_status');
            
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');


            if ($schema_builder->hasIndex('orders_order_status_index'))
                $table->dropIndex('orders_order_status_index');
            
            if ($schema_builder->hasIndex('orders_created_at_index'))
                $table->dropIndex('orders_created_at_index');
            if ($schema_builder->hasIndex('orders_updated_at_index'))
                $table->dropIndex('orders_updated_at_index');
        });
    }

}
