<?php

namespace App\AlertSystem\ControlAlert;

use App\AlertSystem\AlertsV2\FloodAlert;
use App\Entities\AlertSystem\TrackingFloodAlert;
use App\Events\AlertFloodEvent;
use App\Mail\AlertMail;
use App\Repositories\Administrator\StationRepository;
use Carbon\Carbon;
use mysql_xdevapi\Table;
use function Couchbase\defaultDecoder;
use App\Repositories\AlertSystem\UserRepository;
use DB;

class ControlFloodAlert extends ControlAlertBase implements ControlAlertContract
{
    /**
     * ControlFloodAlert constructor.
     * @param Carbon $dateTime
     */
    public function __construct(Carbon $dateTime)
    {
        parent::__construct('flood', $dateTime);

        $this->config();
    }

    public function config()
    {
        foreach ($this->controlAlerts as $controlAlert) {
            $this->alerts[] = new FloodAlert($this->alertCode, $controlAlert, $this->dateTime, $this->initDateTime, $this->finalDateTime);
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
    public function formatDataToEvent()
    {
        $data = DB::table('tracking_flood_alert')
            ->select(DB::raw('distinct on(alert_id, primary_station_id) *'))
            ->orderByRaw('alert_id DESC,primary_station_id DESC, id DESC limit 100')
            ->get();
        $station = new StationRepository();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->station = $station->getAllDataStation($data[$i]->primary_station_id);
        }
        return $data;
    }

    /**
     * Ordena las alertas por prioridad de impresion.
     * @return array Retorna todos los registros ordenados
     */
    public function sortData($data)
    {
        $red = [];
        $green = [];
        foreach ($data as $item) {
            switch ($item->alert_tag) {
                case 'red':
                    array_push($red, $item);
                    break;
                case 'green':
                    array_push($green, $item);
                    break;
            }
        }
        return array_merge($red, $green);
    }

    /**
     * Ejecuta el evento y realiza validaciones para notificar
     */
    public function sendDataToEvent()
    {
        $data = $this->formatDataToEvent();
        event(new AlertFloodEvent($data));
        $this->sendEmailAndMsm($data);
    }

    /**
     * Se encarga de ejecutar una busqueda en los datos para ver si hubó algun cambio en el esto de la alerta  y notificarlo
     * @param $data Espera un arreglo con las últimas alertas calculadas
     */
    public function sendEmailAndMsm($data)
    {
        $arrEmail = [];
        $name = 'Alerta por Inundación';
        $message = 'Cambio Indicadores Inundación (' . (clone($this->initDateTime))->format('Y-m-d H:i:s') . ') - (' . Carbon::now()->format('Y-m-d H:i:s') . ')';
        $code = 'a10';
        $users = new UserRepository();
        $emails = $users->getEmailUserFromAlert($code);
        foreach ($emails as $email) {
            array_push($arrEmail, $email->email);
        }
        foreach ($data as $item) {
            if ($item->alert_status == 'increase') {
//                envia EMAIL
                $dataReal = $this->sortData($data);
                \Mail::to('ideaalertas@gmail.com')->bcc($arrEmail)->send(new AlertMail($name, $dataReal, $message, $code));
                break;
            }
        }
    }
}