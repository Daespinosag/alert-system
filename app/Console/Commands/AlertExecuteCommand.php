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
        $this->executeFloodAlert($date,2);

        # Ejecutar alerta por deslizamientos
        $this->executeLandslideAlert($date,2);
    }

    /**
     * @param array $configurations
     * @param Carbon $initialDate
     * @param Carbon $finalDate
     */
    public function executeLandslideAlert(Carbon $date,int $iter)
    {
        $dateTime = $date;
        for($i=0;$i<$iter;$i++) {
            $extract = ControlLandslideAlert($dateTime);
            $extract->execute();
            $dateTime = $this->generateDateTime($dateTime, '+5 minutes');
        }
    }

    /**
     * @param array $configurations
     * @param Carbon $initialDate
     * @param Carbon $finalDate
     */
    public function executeFloodAlert(Carbon $date,int $iter)
    {
        $dateTime = $date;
        for($i=0;$i<$iter;$i++) {
            $extract = ControlFloodAlert($dateTime);
            $extract->execute();
            $dateTime = $this->generateDateTime($dateTime, '+5 minutes');
        }
    }
}
