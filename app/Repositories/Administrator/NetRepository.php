<?php

namespace App\Repositories\Administrator;

use App\Repositories\RepositoriesContract;
use Illuminate\Support\Collection;
use DB;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Net;

class NetRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Net::class;

    /**
     * @return mixed
     */
    protected function queryBuilder(){
        return DB::connection('administrator')->table('net');
    }

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

    /**
     * @param array $nets
     * @return Collection
     */
    public function getNetsById(array $nets) : Collection {
        return $this->queryBuilder()->select('id','name','description','administrator_name','rt_active')->whereIn('id',$nets)->get();
    }

}