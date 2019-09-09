<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\Neighborhood;
use App\Repositories\AppBaseRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Container\Container;

class NeighborhoodRepository extends AppBaseRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Neighborhood::class;
}