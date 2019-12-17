<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInquiry02Index extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inquiry', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());

        
            if (!$schema_builder->hasIndex('inquiry_status'))
                $table->index('inquiry_status');
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
        Schema::table('inquiry', function(Blueprint $table) {
            $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());


          
            if ($schema_builder->hasIndex('inquiry_status'))
                $table->dropIndex('inquiry_status');
            if ($schema_builder->hasIndex('created_at'))
                $table->dropIndex('created_at');
            if ($schema_builder->hasIndex('updated_at'))
                $table->dropIndex('updated_at');


      
            if ($schema_builder->hasIndex('inquiry_inquiry_status_index'))
                $table->dropIndex('inquiry_inquiry_status_index');
            if ($schema_builder->hasIndex('inquiry_created_at_index'))
                $table->dropIndex('inquiry_created_at_index');
            if ($schema_builder->hasIndex('inquiry_updated_at_index'))
                $table->dropIndex('inquiry_updated_at_index');
        });
    }

}
