<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Spiders\Pinboard;
use Illuminate\Console\Command;
use RoachPHP\Roach;

class GetPinboardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-pinboard-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the data from Pinboard';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // run the roach spider
        Roach::startSpider(Pinboard::class);
    }
}
