<?php

namespace App\Console\Commands;

use App\AlertSystem\Alerts\FloodAlert;
use App\Repositories\Administrator\AlertRepository;
use App\Repositories\AlertSystem\FloodRepository;
use App\Repositories\AlertSystem\UserRepository;
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
        #Se inicializa la fecha a consultar
        $date = Carbon::now();

        #Se incluyen las configurciones geerales
        $configurations = ['sendEmailChanges' => true, 'sendEventData' => true ];

        # Ejecutar alerta por inundacion
        $this->executeFloodAlert($configurations, clone $date,clone $date);

        # Ejecutar alerta por deslizamientos
        $this->executeLandslideAlert($configurations,clone $date,clone $date);
    }

    /**
     * @param array $configurations
     * @param Carbon $initialDate
     * @param Carbon $finalDate
     */
    public function executeLandslideAlert(array $configurations,Carbon $initialDate,Carbon $finalDate)
    {
        # Se configuran las fechas para realizar el calculo
        $configurations['initialDate']  = $initialDate;
        $configurations['finalDate']    = $finalDate;

        # se crea el objeto para consultar la alerta
        $alert = new LandslideAlert(new UserRepository(), new ConnectionRepository(), new StationRepository(), new LandslideRepository(), new AlertRepository(), $configurations);

        # se ejecuta la alerta
        $alert->init();
    }

    /**
     * @param array $configurations
     * @param Carbon $initialDate
     * @param Carbon $finalDate
     */
    public function executeFloodAlert(array $configurations,Carbon $initialDate,Carbon $finalDate)
    {
        # Se configuran las fechas para realizar el calculo
        $configurations['initialDate']  = $initialDate;
        $configurations['finalDate']    = $finalDate;

        # se crea el objeto para consultar la alerta
        $alert = new FloodAlert(new UserRepository(), new ConnectionRepository(), new StationRepository(), new FloodRepository(), new AlertRepository(), $configurations);

        # se ejecuta la alerta
        $alert->init();
    }
}
