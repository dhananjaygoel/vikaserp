<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeliveryChallanTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan', function(Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }

     public function down()
    {
        Schema::table('delivery_challan',function(Blueprint $table){
          $table->dropColumn('deleted_at');
        });
    }

}
