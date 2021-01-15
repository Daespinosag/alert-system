<?php

namespace App\Repositories\Administrator;

use App\Repositories\RepositoriesContract;
use DB;
use Illuminate\Support\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Basin;

class BasinRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Basin::class;

    /**
     * @return mixed
     */
    protected function queryBuilder(){
        return DB::connection('administrator')->table('basin');
    }
    /**
     * @param array $basins
     * @return Collection
     */
    public function getBasinsById(array $basins) : Collection {
        return $this->queryBuilder()->select('id','name','code','description','kml')->whereIn('id',$basins)->get();
    }
}