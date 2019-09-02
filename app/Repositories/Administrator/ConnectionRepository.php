<?php

namespace App\Repositories\Administrator;

use Illuminate\Container\Container;
use App\Entities\Administrator\Connection;
use Rinvex\Repository\Repositories\EloquentRepository;

class ConnectionRepository extends EloquentRepository
{
    /**
     * RepositoriesContract constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)->setModel(Connection::class)->setRepositoryId('rinvex.repository.uniqueid');
    }

    /**
     * @param $variables
     * @return mixed
     *
     */
    public function getStationsNotIn($variables)
    {
        return $this->select('*')->whereNotIn('id',$variables)->get();
    }

    /**
     * @return mixed
     */
    public function searchEtlActive()
    {
        return $this->select('*')->where('etl_active',true)->get();
    }
}