<?php

namespace App\AlertSystem\Traits;

use function Couchbase\defaultDecoder;
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

    /**
     * @param string $externalConnection
     * @param string $tableName
     * @param string $variable
     * @param string $dataOne
     * @param string $timeOne
     * @param string $dataTwo
     * @param string $timeTwo
     * @return mixed
     */
    public function countDataToExtract(
        string $externalConnection,
        string $tableName,
        string $variable,
        string $dataOne,
        string $timeOne,
        string $dataTwo,
        string $timeTwo
    )
    {
        /** TODO Notificar al sistema de errores cuando no se encuentren datos -> no tener datos hace que todo el proceso no se ejecute**/

        $value = DB::connection($externalConnection)
            ->table($tableName)
            ->selectRaw("COUNT($variable) as count")
            ->where($variable,'!=','-')->whereNotNull($variable)
            ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
            ->first();

        return is_null($value) ? 0 : $value->count;
    }

    /**
     * @param string $externalConnection
     * @param string $tableName
     * @param string $variable
     * @param string $dataOne
     * @param string $timeOne
     * @param string $dataTwo
     * @param string $timeTwo
     * @return mixed
     */
    public function getExternalData(
        string $externalConnection,
        string $tableName,
        string $variable,
        string $dataOne,
        string $timeOne,
        string $dataTwo,
        string $timeTwo
    ){
        return DB::connection($externalConnection)
            ->table($tableName)
            ->select('fecha','hora',$variable) #  TODO incluir nivel para las de inundacion nivel
            ->where($variable,'!=','-')
            ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
            ->orderByRaw('fecha DESC , hora DESC')
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