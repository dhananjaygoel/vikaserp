<?php

namespace App\Jobs;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPDFDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct(Request $request)
    // {
    //     $this->request = $request;
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        DB::table('file_info')->where('uuid',$request->uuid)->update(array('status'=> 1));
    }
}
