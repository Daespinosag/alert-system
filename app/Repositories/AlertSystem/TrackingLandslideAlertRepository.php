<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\TrackingLandslideAlert;
use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;

class TrackingLandslideAlertRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = TrackingLandslideAlert::class;
}