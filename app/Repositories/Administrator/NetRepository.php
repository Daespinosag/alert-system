<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Net;

class NetRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Net::class;

    /**
     * @return mixed
     */
    public function getNetName()
    {
        return $this->select('id','name')->where('etl_active',true)->pluck('name','id');
    }

    // The new method from alert-system TODO

    /**
     * @return mixed
     */
    public function getNets()
    {
        return $this->select('*')->get();
    }

}