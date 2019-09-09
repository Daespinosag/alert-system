<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertFlood;
use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;

class AlertFloodRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = AlertFlood::class;
}