<?php

namespace App\Repositories\Administrator;


use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Alert;

class AlertRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Alert::class;

}