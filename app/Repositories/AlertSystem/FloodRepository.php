<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\Flood;
use Carbon\Carbon;
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

    public function getUltimateDate(int $stationId,string $date)
    {
        return $this->select('*')
                    ->where('station','=',$stationId)
                    ->where('date_execution','<', $date)
                    ->orderBy('date_execution', 'desc')
                    ->first();
    }
}