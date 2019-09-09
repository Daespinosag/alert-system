<?php

namespace App\Repositories\Administrator;

use App\Repositories\RepositoriesContract;
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
     * @param array $codes
     * @return mixed
     */
    public function getTypeStations(array $codes)
    {
        return $this->select('*')->whereIn('code',$codes)->get();
    }
}