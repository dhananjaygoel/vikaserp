<?php

use Illuminate\Database\Seeder;
use App\Customer;

class UpdateCustomer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $updateCust = App\Customer::where('company_name','like','%  %')->get();
        foreach($updateCust as $data){
            $company = str_replace('  ', ' ', $data->company_name);
            $tally_name = str_replace('  ', ' ', $data->tally_name);
            DB::table('customers')->where('id',$data->id)->update([
                'company_name' => $company,
                'tally_name' => $tally_name
            ]);
        }
    }
}
