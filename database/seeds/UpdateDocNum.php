<?php

use Illuminate\Database\Seeder;
use App\DeliveryChallan;

class UpdateDocNum extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void doc_number
     */
    public function run()
    {
        $data = App\DeliveryChallan::all();
        foreach($data as $prod){

            $prod->update([
                'doc_number' => null
            ]);
        }
        // DB::table('loaded_bies')->where('first_name','Raxit')->where('last_name','Test')->delete();
        // DB::table('labours')->where('first_name','Raxit')->where('last_name','Test')->delete();
    }
}
