<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Landslide;

class LandslideRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = Landslide::class;

    public  function createShowcase()
    {
        return new $this->model;
    }

    public function getUltimateDate($stationId)
    {
        return $this->select('*')->where('station','=',$stationId)->orderBy('created_at', 'desc')->first();
    }
}