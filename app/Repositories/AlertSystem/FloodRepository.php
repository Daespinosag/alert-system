<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\Flood;
use Rinvex\Repository\Repositories\EloquentRepository;

class FloodRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = Flood::class;

    /**
     * @return mixed
     */
    public  function createShowcase()
    {
        return new $this->model;
    }

    public function getUltimateDate($stationId)
    {
        return $this->select('*')->where('station','=',$stationId)->orderBy('created_at', 'desc')->first();
    }
}