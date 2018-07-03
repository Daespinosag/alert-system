<?php

namespace App\Console\Commands;

use App\AlertSystem\Alerts\FloodAlert;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\AlertSystem\FloodRepository;
use Illuminate\Console\Command;
use App\Repositories\Administrator\ConnectionRepository;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\LandslideRepository;
use App\AlertSystem\Alerts\LandslideAlert;

class AlertExecuteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert-execute';

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
        (new LandslideAlert(new ConnectionRepository(), new StationRepository(), new LandslideRepository(), new AlertRepository()))->init();

        (new FloodAlert(new ConnectionRepository(), new StationRepository(), new FloodRepository(), new AlertRepository()))->init();
    }
}
