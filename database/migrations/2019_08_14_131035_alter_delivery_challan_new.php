<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallan_new extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan', function (Blueprint $table) {
         $table->dateTime('deleted_at')->change();
       });
    }

    
   

}
