<?php

namespace App\Console\Commands;

use App\AlertSystem\Alerts\FloodAlert;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\AlertSystem\FloodRepository;
use Carbon\Carbon;
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
        $date = Carbon::now();

        $configurations = [
            'sendEmailChanges'      => true,
            'sendEventDataChanges'  => false,
            'initialDate'           => $date,
            'finalDate'             => $date,
        ];

        # Ejecutar alerta por inundacion
        $this->executeFloodAlert($configurations);

        # Ejecutar alerta por deslizamientos
        $this->executeLandslideAlert($configurations);
    }

    /**
     * @param array $configurations
     */
    public function executeLandslideAlert(array $configurations)
    {
        (new LandslideAlert(
            new ConnectionRepository(),
            new StationRepository(),
            new LandslideRepository(),
            new AlertRepository(),
            $configurations
            )
        )->init();
    }

    /**
     * @param array $configurations
     */
    public function executeFloodAlert(array $configurations)
    {
        (new FloodAlert(
            new ConnectionRepository(),
            new StationRepository(),
            new FloodRepository(),
            new AlertRepository(),
            $configurations
            )
        )->init();
    }
}
