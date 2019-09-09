<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\TrackingFloodAlert;
use App\Repositories\AppBaseRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Container\Container;

class TrackingFloodAlertRepository extends AppBaseRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = TrackingFloodAlert::class;
}