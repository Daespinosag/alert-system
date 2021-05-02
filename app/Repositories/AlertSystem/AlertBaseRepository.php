<?php
namespace App\Repositories\AlertSystem;

use App\Entities\Administrator\Alert;
use App\Entities\AlertSystem\TrackingFloodAlert;
use App\Entities\AlertSystem\TrackingLandslideAlert;
use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;

class AlertBaseRepository extends EloquentRepository implements RepositoriesContract{


    /**
     * @param int $id
     * @return mixed
     */
    public function getAlert(int $id){
        return $this->select('*')->where('id','=',$id)->first();
    }
}
