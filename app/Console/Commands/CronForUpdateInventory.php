<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Inventory;
use Carbon\Carbon;

class CronForUpdateInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update_inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $is_update = 0;
        $inventory_list = Inventory::first();
        $current = \Carbon\Carbon::now();
        $inventory = new Inventory();
        $last_updated;
        if (isset($inventory_list->opening_qty_date) && $inventory_list->opening_qty_date != NULL) {
            $last_updated = explode(' ', $inventory_list->opening_qty_date);
            $last_updated_date = $last_updated[0];
            $last_updated_time = explode(':', $last_updated[1]);
            $current_date = $current->toDateString();
            if (!$last_updated_date < $current_date) {
                $is_update = $inventory->update_opening_stock();
            }
        } else {
            $is_update = $inventory->update_opening_stock();
        }

        if ($is_update > 0)
            $str = $is_update . " records has been updated at " . $current_date . " " . $current->toTimeString();
        else {
            $str = "No records has been updated. " . $last_updated[0] . " " . $last_updated[1];
        }

        echo $str;
    }
}
