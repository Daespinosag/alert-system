<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use  App\AlertSystem\ControlAlert\ControlFloodAlert;
use  App\AlertSystem\ControlAlert\ControlLandslideAlert;

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

        # Ejecutar alerta por inundacion
        $this->executeFloodAlert($date);

        # Ejecutar alerta por deslizamientos
        $this->executeLandslideAlert($date);
    }

    /**
     * @param Carbon $dateTime Esperan la fecha actual para consultar
     */
    public function executeLandslideAlert(Carbon $dateTime)
    {
            $extract = new ControlLandslideAlert($dateTime);
            $extract->execute();
    }

    /**
     * @param Carbon $dateTime Espera la fecha actual para consultar
     */
    public function executeFloodAlert(Carbon $dateTime)
    {
            $extract = new ControlFloodAlert($dateTime);
            $extract->execute();

    }
}
