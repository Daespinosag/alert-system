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

    public function getUltimateDate(int $stationId,string $date)
    {
        return $this->select('*')
            ->where('station','=',$stationId)
            ->where('date_execution','<', $date)
            ->orderBy('date_execution', 'desc')
            ->first();
    }
}