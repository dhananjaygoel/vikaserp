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
        Schema::query("update delivery_challan set deleted_at = null");
    }

     public function down()
    {
        /*Schema::table('delivery_challan',function(Blueprint $table){
          $table->dropColumn('deleted_at');
        });*/
    }

}
