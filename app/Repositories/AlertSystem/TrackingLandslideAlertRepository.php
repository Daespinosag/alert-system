<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\TrackingLandslideAlert;
use App\Repositories\AppBaseRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Container\Container;

class TrackingLandslideAlertRepository extends AppBaseRepository implements RepositoriesContract
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