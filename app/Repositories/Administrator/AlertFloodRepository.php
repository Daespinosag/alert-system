<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertFlood;
use App\Repositories\RepositoriesContract;
use Illuminate\Support\Facades\DB;
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

    /**
     * @return mixed
     */
    protected function queryBuilder(){
        return DB::connection('administrator')->table('alert_flood');
    }

    public function getAlerts(){
        return $this->select('id','basin_id','name','code','active','limit_red as limitRed','icon')->get();
    }
}