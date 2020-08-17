<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 

class DeleteDC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delivery_challan:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete DC after 48 hours.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = new \DateTime();
        $date->modify('-48 hours');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $select_data = DB::table('file_info')->where('created_at','<',$formatted_date)->get();
        foreach ($select_data as $data){
            $file_path = public_path("upload/invoices/dc/".$data->file_name);
            if(File::exists($file_path)){
                File::delete($file_path);
                DB::table('file_info')->where('uuid',$data->uuid)->update(array('status'=> 4));
            }else{
                // echo $file_path."\n";
            }
        }
        $this->info('File has been successfully deleted from server.');
    }
}
