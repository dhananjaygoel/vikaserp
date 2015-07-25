<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Request;
use Route;

class DeleteRecords extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'records:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all the completed records older than seven days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
//        $this->info("Records deleted!");
        $request = Request::create('clear_completed_records', 'GET');
        return Route::dispatch($request)->getContent();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
//			['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

}
