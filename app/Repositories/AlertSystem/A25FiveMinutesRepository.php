<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\A25FiveMinutes;


class A25FiveMinutesRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = A25FiveMinutes::class;

    public  function createShowcase()
    {
        return new $this->model;
    }

    public function getUltimateDate($stationId)
    {
        return $this->select('*')->where('station','=',$stationId)->orderBy('created_at', 'desc')->first();
    }
}