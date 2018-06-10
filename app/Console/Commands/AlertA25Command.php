<?php

namespace App\Console\Commands;

use App\Repositories\Administrator\AlertRepository;
use Illuminate\Console\Command;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\A25FiveMinutesRepository;
use App\AlertSystem\Alerts\AlertA25;

class AlertA25Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert-a25';

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
        $alertSystem = new AlertA25(
            new ConnectionRepository(),
            new StationRepository(),
            new A25FiveMinutesRepository(),
            new AlertRepository()
        );

        $alertSystem->init();
    }
}
