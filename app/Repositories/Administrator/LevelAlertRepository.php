<?php

namespace App\Repositories\Administrator;


use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\LevelAlert;

class LevelAlertRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = LevelAlert::class;
}