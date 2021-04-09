<?php


namespace App\Repositories\AlertSystem;


use App\Repositories\RepositoriesContract;

interface AlertContractRepository extends RepositoriesContract
{
    /**
     * @param int $stationId
     * @param string $date
     * @return mixed
     */
    public function getUltimateDate(int $stationId, string $date);

    /**
     * @param int $stationId
     * @param string $dateOne
     * @param string $dateTwo
     * @return mixed
     */
    public function getBetweenData(int $stationId,string $dateOne,string $dateTwo);

}