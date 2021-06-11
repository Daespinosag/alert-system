<?php

namespace App\Repositories\Administrator;

use App\Repositories\AlertSystem\LogsRepository;
use Illuminate\Support\Collection;
use App\Repositories\EloquentRepository;
use App\Entities\Administrator\Station;
use DB;

class StationRepository extends EloquentRepository
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Station::class;

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        try {
            return DB::connection('administrator')->table('station');
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|queryBuilder|No pudo conectar';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getStation($stationId)
    {
        try {
            return $this->select('*')->where('id', $stationId)->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|getStation|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $stationId
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getAllDataStation($stationId)
    {
        try {
            return $this->queryBuilder()->select('station.name')->where('id', $stationId)->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|getAllDataStation|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $stationId
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @param array $alertsCode
     * @return mixed
     */
    public function getStationsFromAlertsForMaps(array $alertsCode)
    {
        try {
            return $this->select('*')
                ->where('station.active', '=', true)
                ->where('station.rt_active', '=', true)
                ->whereHas('alerts', function ($query) use ($alertsCode) {
                    $query->whereIn('alert.code', $alertsCode)
                        ->where('alert_station.active', '=', true)
                        ->where('alert.active', '=', true);
                })
                ->with(['typeStation', 'net', 'alerts' => function ($query) use ($alertsCode) {
                    $query->whereIn('code', $alertsCode);
                }])
                ->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|getStationsFromAlertsForMaps|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $alertsCode
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getStationsAlertFloodToMap()
    {
        try {
            return $this->queryBuilder()
                ->select(
                    'station.id as id',
                    'station_flood_alert.id as station_alert_id',
                    'station.station_type_id as station_type_id',
                    'station.net_id as net_id',
                    'station_flood_alert.flood_alert_id as alert_id',
                    'station.name as name',
                    'station.city as city',
                    'station_flood_alert.active as active',
                    'station_flood_alert.primary as primary',
                    'station_flood_alert.visible as visible',
                    'station_flood_alert.distance as distance',
                    'station.latitude_degrees',
                    'station.latitude_minutes',
                    'station.latitude_seconds',
                    'station.latitude_direction',
                    'station.longitude_degrees',
                    'station.longitude_minutes',
                    'station.longitude_seconds',
                    'station.longitude_direction'
                )
                ->join('station_flood_alert', 'station_flood_alert.station_id', '=', 'station.id')
                ->where('station_flood_alert.active', '=', true)
                ->where('station_flood_alert.visible', '=', true)
                ->where('station.active', '=', true)
                ->where('station.rt_active', '=', true)
                ->orderBY('station_flood_alert.id')
                ->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|getStationsAlertFloodToMap|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getStationsAlertLandslideToMap()
    {
        try {
            return $this->queryBuilder()
                ->select(
                    'station.id as id',
                    'station_landslide_alert.id as station_alert_id',
                    'station.station_type_id as station_type_id',
                    'station.net_id as net_id',
                    'station_landslide_alert.landslide_alert_id as alert_id',
                    'station.name as name',
                    'station.city as city',
                    'station_landslide_alert.active as active',
                    'station_landslide_alert.primary as primary',
                    'station_landslide_alert.visible as visible',
                    'station_landslide_alert.distance as distance',
                    'station.latitude_degrees',
                    'station.latitude_minutes',
                    'station.latitude_seconds',
                    'station.latitude_direction',
                    'station.longitude_degrees',
                    'station.longitude_minutes',
                    'station.longitude_seconds',
                    'station.longitude_direction'
                )
                ->join('station_landslide_alert', 'station_landslide_alert.station_id', '=', 'station.id')
                ->where('station_landslide_alert.active', '=', true)
                ->where('station_landslide_alert.visible', '=', true)
                ->where('station.active', '=', true)
                ->where('station.rt_active', '=', true)
                ->orderBY('station_landslide_alert.id')
                ->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|getStationsAlertLandslideToMap|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function getUltimateDataInAlertTable($table, $stationId)
    {
        try {
            return DB::connection('alert-system')->table($table)->select('*')->where('station', '=', $stationId)->orderBy('created_at', 'desc')->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'StationRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|StationRepository|getUltimateDataInAlertTable|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $table,
                    $stationId
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @param string $alertCode
     * @param int $alertId
     * @param bool $primary
     * @return Collection
     */
    public function getStationsAlerts(string $alertCode, int $alertId, bool $primary = false): Collection
    {
        return $this->queryBuilder()
            ->select(
                'station.id as station_sk',
                'net.id as net_id',
                'station.name as station_name',
                'net.name as net_name',
                'connection.name as connection_name',
                'station.table_db_name as station_table',
                'station_type.code as station_type_code',
                'station_' . $alertCode . '_alert.primary as station_alert_primary',
                'station_' . $alertCode . '_alert.active as station_alert_active',
                'station_' . $alertCode . '_alert.visible as station_alert_visible',
                'station_' . $alertCode . '_alert.distance as station_alert_distance'
            )
            ->join('station_' . $alertCode . '_alert', 'station_' . $alertCode . '_alert.station_id', '=', 'station.id')
            ->join('net', 'net.id', '=', 'station.net_id')
            ->join('connection', 'connection.id', '=', 'net.connection_id')
            ->join('station_type', 'station_type.id', '=', 'station.station_type_id')
            ->where('station_' . $alertCode . '_alert.primary', '=', $primary)
            ->where('station_' . $alertCode . '_alert.' . $alertCode . '_alert_id', '=', $alertId)
            ->where('station_' . $alertCode . '_alert.active', '=', true)
            ->where('station_' . $alertCode . '_alert.visible', '=', true)
            ->where('station.active', '=', true)
            ->where('station.rt_active', '=', true)
            ->orderBY('station.id')
            ->get();
    }

    public function getAllStationFlood()
    {
        return $this->queryBuilder()
            ->select(
                'station.id as station_sk',
                'net.id as net_id',
                'station.name as station_name',
                'net.name as net_name',
                'connection.name as connection_name',
                'station.table_db_name as station_table',
                'station_type.code as station_type_code',
                'station_flood_alert.primary as station_alert_primary'
            )
            ->join('station_flood_alert', 'station_flood_alert.station_id', '=', 'station.id')
            ->join('net', 'net.id', '=', 'station.net_id')
            ->join('connection', 'connection.id', '=', 'net.connection_id')
            ->join('station_type', 'station_type.id', '=', 'station.station_type_id')
            //->where('station_landslide_alert.landslide_alert_id','=',1)
            ->where('station_flood_alert.active', '=', true)
            ->where('station_flood_alert.visible', '=', true)
            ->where('station.active', '=', true)
            ->where('station.rt_active', '=', true)
            ->orderBY('station.id')
            ->get();
    }

    public function getAllDataStationById($stationId, $alertId, $alertType)
    {
        $data = $this->queryBuilder()
            ->select(
                'station.id',
                'station.name',
                'station.city',
                'station.localization',
                'station.basin',
                'station.sub_basin',
                'station_type.name as name_type',
                'station_type.code',
                'station_type.description')
            ->join('net', 'net.id', '=', 'station.net_id')
            ->join('station_type', 'station_type.id', '=', 'station.station_type_id')
            ->where('station.id', '=', $stationId)
            ->get();

        for ($i = 0; $i < count($data); $i++) {

            if ($alertType == 'flood') {
                $data[$i]->StationsSeconds = $this->queryBuilder()->select(
                    'station.id',
                    'station.name',
                    'station.city',
                    'station.localization',
                    'station.basin',
                    'station.sub_basin',
                    'station_type.name as name_type',
                    'station_type.code',
                    'station_type.description')
                    ->join('station_type', 'station_type.id', '=', 'station.station_type_id')
                    ->join('station_flood_alert', 'station_flood_alert.station_id', '=', 'station.id')
                    ->where('station_flood_alert.flood_alert_id', '=', $alertId)
                    ->where('station_flood_alert.primary', '=', 'false')
                    ->get();
            } else if ($alertType == 'landslide') {
                $data[$i]->StationsSeconds = $this->queryBuilder()->select(
                    'station.id',
                    'station.name',
                    'station.city',
                    'station.localization',
                    'station.basin',
                    'station.sub_basin',
                    'station_type.name as name_type',
                    'station_type.code',
                    'station_type.description')
                    ->join('station_type', 'station_type.id', '=', 'station.station_type_id')
                    ->join('station_landslide_alert', 'station_landslide_alert.station_id', '=', 'station.id')
                    ->where('station_landslide_alert.flood_alert_id', '=', $alertId)
                    ->where('station_landslide_alert.primary', '=', 'false')
                    ->get();
            }
        }
        return $data;
    }
}