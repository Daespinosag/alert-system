<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\A25FiveMinutesRepository;
use App\AlertSystem\AlertSystem;

class AlertSystemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'execute class AlertSystem';

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
        $alertSystem = new AlertSystem(new ConnectionRepository(),new StationRepository(),new A25FiveMinutesRepository());
        $alertSystem->init();
    }
}
