<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Net;

class NetRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Net::class;

    public function getNetName()
    {
        return $this->select('id','name')->where('etl_active',true)->pluck('name','id');
    }

}