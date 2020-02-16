<?php

namespace App\Repositories\Administrator;

use App\Repositories\RepositoriesContract;
use DB;
use Illuminate\Support\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\StationType;

class StationTypeRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = StationType::class;

    /**
     * @return mixed
     */
    protected function queryBuilder(){
        return DB::connection('administrator')->table('station_type');
    }

    /**
     * @param array $codes
     * @return mixed
     */
    public function getTypeStations(array $codes)
    {
        return $this->select('*')->whereIn('code',$codes)->get();
    }

    /**
     * @param array $stationTypes
     * @return Collection
     */
    public function getStationTypeById(array $stationTypes) : Collection {
        return $this->queryBuilder()->select('id','name','code','etl_method','description')->whereIn('id',$stationTypes)->get();
    }
}