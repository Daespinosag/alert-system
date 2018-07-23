<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\StationType;

class StationTypeRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = StationType::class;

    public function getTypeStations(array $codes)
    {
        return $this->select('*')->whereIn('code',$codes)->get();
    }
}