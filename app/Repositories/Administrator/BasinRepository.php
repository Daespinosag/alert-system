<?php

namespace App\Repositories\Administrator;

use App\Repositories\RepositoriesContract;
use App\Repositories\AppBaseRepository;
use App\Entities\Administrator\Basin;

class BasinRepository extends AppBaseRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Basin::class;
}