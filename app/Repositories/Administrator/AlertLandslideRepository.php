<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertLandslide;
use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;

class AlertLandslideRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = AlertLandslide::class;


    public function getAlerts(){
        return $this->select('id','zone_id','name','code','active','limit_yellow as limitYellow','limit_orange as limitOrange','limit_red as limitRed','icon')->get();
    }
}