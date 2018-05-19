<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Map;

class MapRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Map::class;
}