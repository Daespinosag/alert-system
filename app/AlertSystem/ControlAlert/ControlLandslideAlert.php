<?php

namespace App\AlertSystem\ControlAlert;

use App\AlertSystem\ControlAlert\AlertContract;
use App\AlertSystem\AlertsV2\LandslideAlert;
use App\Events\AlertFloodEvent;
use Carbon\Carbon;

class ControlLandslideAlert extends ControlAlertBase implements ControlAlertContract
{

    /**
     * ControlFloodAlert constructor.
     * @param array $primaryStations
     * @param Carbon $dateTime
     */
    public function __construct(Carbon $dateTime)
    {
        parent::__construct('landslide', $dateTime);
        $this->config();
    }

    public function config()
    {
        foreach ($this->controlAlerts as $controlAlert) {
            $this->alerts[] = new LandslideAlert($this->alertCode, $controlAlert, $this->dateTime, $this->initDateTime, $this->finalDateTime);
        }
    }

    public function execute()
    {
        foreach ($this->alerts as $alert) {
            $alert->execute();
        }
        $this->sendDataToEvent();
    }

    /**
     * Recupera los últimos datos de las estaciones y las alertas con sus estados
     * @return array Retorna todos los registros
     */
    public function formatDataToEvent(): array
    {
        $data = DB::table('tracking_landslide_alert')
            ->select(DB::raw('distinct on(alert_id, primary_station_id) *'))
            ->orderByRaw('alert_id DESC,primary_station_id DESC, id DESC limit 100')
            ->get();
        return $data;
    }

    /**
     * Ejecuta el evento y realiza validaciones para notificar
     */
    public function sendDataToEvent()
    {
        $data = $this->formatDataToEvent();
        event(new AlertFloodEvent($data));
    }

    /**
     * Se encarga de ejecutar una busqueda en los datos para ver si hubó algun cambio en el esto de la alerta  y notificarlo
     * @param $data Espera un arreglo con las últimas alertas calculadas
     */
    public function sendEmailAndMsm($data)
    {
        foreach ($data as $item) {
            if ($item->alert_tag != 'green') {
//                envia EMAIL

            }
        }
    }
}