<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use  App\AlertSystem\ControlAlert\ControlFloodAlert;
use  App\AlertSystem\ControlAlert\ControlLandslideAlert;
use Illuminate\Support\Facades\Storage;


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
     *
     */
    public function testMode($config)
    {
        if ($config['floodAlert']) {
            # Ejecutar alerta por inundacion
            $this->executeTestModeFloodAlert($config);

        }
        if ($config['landslideAlert']) {
            # Ejecutar alerta por deslizamientos
            $this->executeTestModeLandslideAlert($config);
        }
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
     * @param Carbon $dateTime
     */
    public function executeTestModeLandslideAlert($config)
    {
        $content = "";
        $dateInit = $config['initialDate'];
        $content .= 'Inicio {' . Carbon::now() . '}\\n';
        for ($i = 0; $i < $config['windowTemp']; $i++) {
            $content .= $dateInit . '\\t|';
            if($i % 4 == 0){
                $content .= '\\n';
            }
            $extract = new ControlLandslideAlert($dateInit, $config);
            $extract->execute();
            $dateInit = $this->generateDateTime($dateInit, '+5 minutes');
        }
        $content .= '\\nFin {' . Carbon::now() . '}';
        Storage::disk('local')->put($config['initialDate'] . '-test-landslide.txt', $content);
    }

    /**
     * @param Carbon $dateTime Espera la fecha actual para consultar
     */
    public function executeFloodAlert(Carbon $dateTime)
    {
        $extract = new ControlFloodAlert($dateTime);
        $extract->execute();

    }

    /**
     * @param Carbon $dateTime
     */
    public function executeTestModeFloodAlert($config)
    {

        $content = "";
        $dateInit = $config['initialDate'];
        $content .= 'Inicio {' . Carbon::now() . '}\\n';
        for ($i = 0; $i < $config['windowTemp']; $i++) {
            $content .= $dateInit . '\\t|';
            if($i % 4 == 0){
                $content .= '\\n';
            }
            $extract = new ControlFloodAlert($dateInit, $config);
            $extract->execute();
            $dateInit = $this->generateDateTime($dateInit, '+5 minutes');
        }
        $content .= '\\nFin {' . Carbon::now() . '}';
        Storage::disk('local')->put($config['initialDate'] . '-test-flood.txt', $content);
    }

    /**
     * @param Carbon $dateTime
     * @param string $time
     * @return Carbon
     */
    public function generateDateTime(Carbon $dateTime, string $time): Carbon
    {
        return date_add(clone($dateTime), date_interval_create_from_date_string($time));
    }
}
