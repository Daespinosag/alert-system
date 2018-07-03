<?php

namespace App\Repositories\Administrator;

use DB;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Alert;

class AlertRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Alert::class;

    // The new method from alert-system TODO

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        return DB::connection('administrator')->table('alert');
    }

    /**
     * @param $alertCode
     * @return mixed
     */

   public function getLevelAlert($alertCode)
   {
      return $this->queryBuilder()
                ->select('alert.id','alert.code','level_alert.*')
                ->join('level_alert','level_alert.alert_id','=','alert.id')
                ->where('alert.code','=',$alertCode)
                ->orderBy('level_alert.level','DESC')
                ->get();
  }

}