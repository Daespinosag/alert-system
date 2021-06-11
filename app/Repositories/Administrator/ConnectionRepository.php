<?php

namespace App\Repositories\Administrator;

use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Container\Container;
use App\Entities\Administrator\Connection;
use App\Repositories\EloquentRepository;

class ConnectionRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Connection::class;

    /**
     * @param $variables
     * @return mixed
     *
     */
    public function getStationsNotIn($variables)
    {
        return $this->select('*')->whereNotIn('id', $variables)->get();
    }

    /**
     * @return mixed
     */
    public function searchEtlActive()
    {
        return $this->select('*')->where('etl_active', true)->get();
    }
}