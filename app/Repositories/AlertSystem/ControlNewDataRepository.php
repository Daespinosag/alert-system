<?php

namespace App\Repositories\AlertSystem;

use App\Entities\AlertSystem\ControlNewData;
use App\Repositories\RepositoriesContract;
use Illuminate\Support\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;

class ControlNewDataRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = ControlNewData::class;

    /**
     * @param string $alertCode
     * @return Collection
     */
    public function getUnsettledAlerts(string $alertCode): Collection
    {

        return $this->select('*')->where('alert_code', '=', $alertCode)->where('active', '=', true)->where('homogenization', '=', false)->get();
    }

    /**
     * @param string $alertCode
     * @return Collection
     */
    public function getUnsettledAlertsSpecific(string $alertCode, $alertsId): Collection
    {

        return $this->select('*')->where('alert_code', '=', $alertCode)->where('active', '=', true)->where('homogenization', '=', false)->whereIn('id', $alertsId)->get();
    }
}