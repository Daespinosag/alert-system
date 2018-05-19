<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Graph;

class GraphRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Graph::class;
}