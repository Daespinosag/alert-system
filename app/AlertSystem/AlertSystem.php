<?php

namespace App\AlertSystem;


use App\Events\AlertFiveMinutesCalculated;
use App\Repositories\Administrator\StationRepository;
use App\Repositories\AlertSystem\A25FiveMinutesRepository;
use App\Repositories\Administrator\ConnectionRepository;
use App\AlertSystem\Connection\DatabaseConfig;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AlertSystem
{
    use DatabaseConfig;

    private $connectionRepository;

    private $stationRepository;

    private $a25FiveMinutesRepository;

    public $constData = 7200;

    public $constDays = 24;

    public function  __construct(
        ConnectionRepository $connectionRepository,
        StationRepository $stationRepository,
        A25FiveMinutesRepository $a25FiveMinutesRepository
    )
    {
        $this->connectionRepository = $connectionRepository;
        $this->stationRepository = $stationRepository;
        $this->a25FiveMinutesRepository = $a25FiveMinutesRepository;
    }


    public function init()
    {
        $stations = $this->stationRepository->getForAlertSystem();
        $actualDateTime = Carbon::now();
        $dateTwo = $actualDateTime->format('Y-m-d');
        $dateOne = $actualDateTime->addDay(-$this->constDays)->format('Y-m-d');
        $time = $actualDateTime->format('H:i:s');

        $arrayNewValues = [];

        foreach ($stations as $station) {

            $showcaseA25Table = $this->a25FiveMinutesRepository->createShowcase();
            $ultimateDateA25 = $this->a25FiveMinutesRepository->getUltimateDate($station->id);

            $connection = $this->connectionRepository->findOrFail($station->owner_net_id);
            $this->searchExternalConnection($connection,$station->table_db_name);
            $result = $this->calculateA25($station,$dateOne,$dateTwo,$time);

            $showcaseA25Table->station = $station->id;

            if (!is_null($result->a25)){
                $showcaseA25Table->a25_value = $result->a25;
                $showcaseA25Table->alert = $this->exterminateAlert($result->a25);
            }

            $showcaseA25Table->avg_recovered = round ($result->count / $this->constData * 100,2);

            if (!is_null($ultimateDateA25)){
                $showcaseA25Table->dif_previous_a25 = abs(round($ultimateDateA25->a25_value - $showcaseA25Table->a25_value,2));

                if ($showcaseA25Table->alert == $ultimateDateA25->alert){
                    $showcaseA25Table->num_not_change_alert = $ultimateDateA25->num_not_change_alert + 1;
                }else{
                    $showcaseA25Table->num_not_change_alert = 0;
                    $showcaseA25Table->change_alert = true;

                    if ($showcaseA25Table->alert < $ultimateDateA25->alert){ $showcaseA25Table->alert_decrease = true;}
                    else{$showcaseA25Table->alert_increase = true;}
                }
            }

            $value = $this->a25FiveMinutesRepository->create($showcaseA25Table->toArray());
            array_push($arrayNewValues,$value);
        }

        event(new AlertFiveMinutesCalculated($arrayNewValues));
    }

    public function exterminateAlert($value)
    {
        $maxAlertOne = 200;
        $maxAlertTwo = 300;
        $maxAlertThree = 400;

        $response = 0;

        switch ($value) {
            case ($value <= $maxAlertOne):
                $response = 1;
                break;
            case ($value <= $maxAlertTwo):
                $response = 2;
                break;
            case ($value <= $maxAlertThree):
                $response = 3;
                break;
        }

        return $response;
    }

    public function calculateA25($station,$dataOne,$dataTwo,$time)
    {
        #retorna el valor A25 y el contador de datos recuperados
        return DB::connection('external_connection')->table($station->table_db_name)
            ->select(DB::raw('sum(precipitacion_real) as a25, count(precipitacion_real) as count'))
            ->where('fecha', '=', $dataOne)
            ->where('hora','>=',$time)
            ->orWhere('fecha','>=',$dataOne)
            ->where('fecha','<=',$dataTwo)
            ->orWhere('fecha','=',$dataTwo)
            ->where('hora','<=',$time)
            ->where('precipitacion_real', '<>','-')
            ->get()[0];
    }

}