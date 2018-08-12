<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Station;
use DB;

class StationRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Station::class;

    protected function queryBuilder()
    {
        return DB::connection('administrator')->table('station');
    }

    public function getEtlActive()
    {
        return $this->select('*')->where('etl_active', true)->get();
    }

    public function getStation($stationId)
    {
        return $this->select('*')->where('id',$stationId)->first();
    }

    /**
     * @param int $stationId
     * @return mixed
     * @throws \Rinvex\Repository\Exceptions\RepositoryException
     */

    public function findRelationship(int $stationId)
    {
        return $this->createModel()->with(['originalState','filterState','typeStation'])->find($stationId);
    }

    public function getTypeStation($id)
    {
        return $this->queryBuilder()
                    ->select('station_type.*')
                    ->join('station_type','station.station_type_id','=', 'station_type.id')
                    ->where('station.id',$id)
                    ->first();
    }
    /**
     * @param $stationId
     * @return
     */
    public function findVarForFilter($stationId)
    {
        return DB::connection('administrator')
            ->table('variable')
            ->select(
                'variable_station.id',
                'variable_station.variable_id',
                'variable_station.station_id',
                'variable.name',
                'variable.excel_name',
                'variable.database_field_name',
                'variable.local_name',
                'variable.reliability_name',
                'variable.decimal_precision',
                'variable.unit',
                'variable.correct_serialization',
                'variable_station.maximum',
                'variable_station.minimum',
                'variable_station.previous_deference',
                'variable_station.correction_type',
                'variable.description'
            )
            ->where('variable_station.station_id',  $stationId)
            ->where('variable_station.etl_active', true)
            ->join('variable_station', 'variable.id', '=', 'variable_station.variable_id')
            ->get();
    }

    public function getStationInServerAcquisition()
    {
        return $this->queryBuilder()->select('station.id','station.name','original_state.current_date','original_state.current_time')
                    ->where('station.etl_active', true)
                    ->join('original_state','original_state.station_id','=','station.id')
                    ->join('station_type','station.station_type_id','=', 'station_type.id')
                    ->where('station_type.etl_method', '=', 'weather')
                    ->get();
    }

    public function getStationsForFilterETL()
    {
        return $this->queryBuilder()
                    ->select('station.id','station.name','station.net_id','filter_state.current_date','filter_state.current_time')
                    ->join('filter_state','station.id','=', 'filter_state.station_id')
                    ->join('station_type','station.station_type_id','=', 'station_type.id')
                    ->whereNotNull('station_type.etl_method')
                    ->where('station.etl_active',true)
                    ->where('filter_state.updated',false)
                    ->whereNotNull('station.table_db_name')
                    ->orderby('station.id','ASC')
                    //->limit(1)
                    ->get();
    }

    public function getStationsForOriginalETL()
    {
        return $this->queryBuilder()
                    ->select('station.id','station.name','station.net_id','original_state.current_date','original_state.current_time')
                    ->join('original_state','station.id','=', 'original_state.station_id')
                    ->join('station_type','station.station_type_id','=', 'station_type.id')
                    ->whereNotNull('station_type.etl_method')
                    ->where('station.etl_active',true)
                    ->where('original_state.updated',false)
                    ->whereNotNull('station.table_db_name')
                    ->orderby('station.id','ASC')
                    //->limit(1)
                    ->get();

    }

    public function getStationEtlActive()
    {
        return $this->queryBuilder()
                    ->select('station.id', 'station.name')
                    ->join('net','station.net_id','=','net.id')
                    ->join('station_type','station.station_type_id','=', 'station_type.id')
                    ->whereNotNull('station_type.etl_method')
                    ->where('station.etl_active',true)
                    ->where('net.etl_active',true)
                    ->orderby('station.name','ASC')
                    ->get();
    }

    public function getStationsForEtl()
    {
        return $this->select('id','net_id')->where('etl_active',true)->get();
    }
    public function getIdNetForIdStation($stationId)
    {
        return $this->select('net_id as id')->where('id',$stationId)->first();
    }
    public function getStationForNetEtlActive($netId)
    {
        return $this->queryBuilder()
                    ->select('station.id','station.net_id','station.station_type_id','station.name')
                    ->join('station_type','station.station_type_id','=', 'station_type.id')
                    ->where('station.net_id',$netId)
                    ->whereNotNull('station_type.etl_method')
                    ->get();
    }

    public function getStationsForTypology($type)
    {
        return $this->queryBuilder()
            ->select('station.id as id','station.name as name')
            ->join('station_type','station.station_type_id','=', 'station_type.id')
            ->where('station_type.etl_method',$type)
            ->orderby('name','ASC')
            ->get();
    }

    public function getIdStationsForTypology($type)
    {
        return $this->queryBuilder()
                ->select('station.id')
                ->join('station_type','station.station_type_id','=', 'station_type.id')
                ->where('station_type.etl_method',$type)
                ->pluck('id')
                ->toArray();
    }

    public function countMissingDataForStation($fact_table,$station_id){
        return DB::connection('data_warehouse')
                    ->table($fact_table)
                    ->select(DB::raw("station_dim.station_sk, station_dim.name, date_dim.date_sk, date_dim.date, count($fact_table.time_sk)"))
                    ->where("$fact_table.station_sk", '=',$station_id)
                    ->join('station_dim', $fact_table.'.station_sk', '=', 'station_dim.station_sk')
                    ->join('date_dim', $fact_table.'.date_sk', '=', 'date_dim.date_sk')
                    ->groupBy("station_dim.station_sk", "station_dim.name", "date_dim.date_sk", "date_dim.date")
                    ->orderBY('date_dim.date_sk')
                    ->get()
                    ->toArray();
    }

    public function countReportData($station)
    {

    }

    // The new method from alert-system TODO

    /**
     * @param string $alertCode
     * @param array $stationsId
     * @return mixed
     */
    public function getForAlertSystem(string $alertCode,array $stationsId = null)
    {
        $data =  $this->queryBuilder()
                ->select(
                    'station.id',
                    'station.name',
                    'station.station_type_id',
                    'station.net_id',
                    'net.connection_id',
                    'station.table_db_name',
                    'alert.name as alertName',
                    'alert.code',
                    'alert_station.flag_level_one',
                    'alert_station.flag_level_two',
                    'alert_station.flag_level_three'
                )
                ->join('alert_station', 'alert_station.station_id', '=', 'station.id')
                ->join('alert', 'alert.id', '=', 'alert_station.alert_id')
                ->join('net','net.id','=','station.net_id')
                ->where('alert_station.active','=',true)
                ->where('alert.code', '=', $alertCode)
                ->where('station.active','=',true);

        if (!is_null($stationsId)) { $data->whereIn('station.id',$stationsId);}

        return $data->orderBY('station.id')->get();
    }

    /**
     * @param string $alertCode
     * @return mixed
     */
    public function getStationsForMaps(string $alertCode)
    {
        return $this->queryBuilder()
                    ->select(
                        'station.id',
                        'station.name',
                        'station.station_type_id',
                        'station.net_id',
                        'net.name as netName',
                        'station.localization',
                        'station.city',
                        'station.latitude_degrees',
                        'station.latitude_minutes',
                        'station.latitude_seconds',
                        'station.latitude_direction',
                        'station.longitude_degrees',
                        'station.longitude_minutes',
                        'station.longitude_seconds',
                        'station.longitude_direction',
                        'alert.name as alertName',
                        'alert.code'
                    )
                    ->join('alert_station', 'alert_station.station_id', '=', 'station.id')
                    ->join('alert', 'alert.id', '=', 'alert_station.alert_id')
                    ->join('net','net.id','=','station.net_id')
                    ->where('alert_station.active','=',true)
                    ->where('alert.code', '=', $alertCode)
                    ->where('station.active','=',true)
                    ->where('station.rt_active','=',true)
                    ->orderBY('station.id')
                    ->get();
    }

    public function getStationsFromAlertsForMaps(array $alertsCode)
    {
        return  $this->select('*')
                ->where('station.active','=',true)
                ->where('station.rt_active','=',true)
                ->whereHas('alerts',function ($query) use ($alertsCode) {
                    $query->whereIn('alert.code', $alertsCode)
                        ->where('alert_station.active', '=', true)
                        ->where('alert.active', '=', true);
                })
                ->with(['typeStation','net','alerts' => function ($query) use ($alertsCode){ $query->whereIn('code', $alertsCode); }])
                ->get();
    }

    public function getUltimateDataInAlertTable($table,$stationId)
    {
        return DB::connection('alert-system')->table($table)->select('*')->where('station','=',$stationId)->orderBy('created_at', 'desc')->first();
    }

}