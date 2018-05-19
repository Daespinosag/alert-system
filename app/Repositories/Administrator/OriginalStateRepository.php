<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\OriginalState;

class OriginalStateRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = OriginalState::class;



}