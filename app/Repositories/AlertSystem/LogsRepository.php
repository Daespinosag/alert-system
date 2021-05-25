<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Logs;

class LogsRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Logs::class;

    /**
     * @param string $code
     * @return mixed
     */
    public function getAll()
    {
        return $this->select('*')->get();
    }

}