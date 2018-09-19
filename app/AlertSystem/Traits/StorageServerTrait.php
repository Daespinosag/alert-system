<?php

namespace App\AlertSystem\Traits;

use DB;

trait StorageServerTrait
{

    /**
     * @param string $externalConnection
     * @param string $tableName
     * @param string $dataOne
     * @param string $timeOne
     * @param string $dataTwo
     * @param string $timeTwo
     * @param int $constData
     * @return mixed
     */
    public function calculateA25(
        string $externalConnection,
        string $tableName,
        string $dataOne,
        string $timeOne,
        string $dataTwo,
        string $timeTwo,
        int $constData
    )
    {
        return DB::connection($externalConnection)
            ->table($tableName)
            ->select('fecha','hora','precipitacion_real')
            ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
            ->orderByRaw('fecha DESC , hora DESC')
            ->limit($constData)
            ->get()
            ->toArray();
    }

    /**
     * @param string $externalConnection
     * @param string $tableName
     * @param string $dataOne
     * @param string $timeOne
     * @param string $dataTwo
     * @param string $timeTwo
     * @param int $constData
     * @return mixed
     */
    public function calculateA10
    (
        string $externalConnection,
        string $tableName,
        string $dataOne,
        string $timeOne,
        string $dataTwo,
        string $timeTwo,
        int $constData
    )
    {
        return DB::connection($externalConnection)
            ->table($tableName)
            ->select('fecha','hora','precipitacion_real')
            ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
            ->orderByRaw('fecha DESC , hora DESC')
            ->limit($constData)
            ->get()
            ->toArray();
    }



    /*
     DB::connection($externalConnection)
            ->table($tableName)
            ->select('fecha','hora','precipitacion_real')#DB::raw('sum(precipitacion_real) as a10, count(precipitacion_real) as count')
            ->where('fecha', '=', $dataOne)
            ->where('hora','>=',$timeOne)
            ->where('fecha','=',$dataTwo)
            ->where('hora','<=',$timeTwo)
            ->get();
    */

}