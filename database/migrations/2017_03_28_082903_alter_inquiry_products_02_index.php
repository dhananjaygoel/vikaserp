<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInquiryProducts02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inquiry_products', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

            if (!$schema_builder->hasIndex('inquiry_id'))
                $table->index('inquiry_id');
            if (!$schema_builder->hasIndex('product_category_id'))
                $table->index('product_category_id');
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
        Schema::table('inquiry_products', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


            if ($schema_builder->hasIndex('inquiry_id'))
                $table->dropIndex('inquiry_id');
            if ($schema_builder->hasIndex('product_category_id'))
                $table->dropIndex('product_category_id');
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');


            if ($schema_builder->hasIndex('inquiry_products_inquiry_id_index'))
                $table->dropIndex('inquiry_products_inquiry_id_index');
            if ($schema_builder->hasIndex('inquiry_products_product_category_id_index'))
                $table->dropIndex('inquiry_products_product_category_id_index');
            if ($schema_builder->hasIndex('inquiry_products_created_at_index'))
                $table->dropIndex('inquiry_products_created_at_index');
            if ($schema_builder->hasIndex('inquiry_products_updated_at_index'))
                $table->dropIndex('inquiry_products_updated_at_index');
        });
    }

}
