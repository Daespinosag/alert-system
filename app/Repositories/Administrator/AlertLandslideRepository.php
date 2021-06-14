<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertLandslide;
use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use App\Repositories\EloquentRepository;

class AlertLandslideRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = AlertLandslide::class;


    public function getAlerts()
    {
        return $this->select('id', 'zone_id', 'name', 'code', 'active', 'limit_yellow as limitYellow', 'limit_orange as limitOrange', 'limit_red as limitRed', 'icon')->get();
    }
}