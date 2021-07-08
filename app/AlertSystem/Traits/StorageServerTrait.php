<?php

namespace App\AlertSystem\Traits;

use App\Helpers\Log;
use Exception;
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
        try {

            return DB::connection($externalConnection)
                ->table($tableName)
                ->select('fecha', 'hora', 'precipitacion_real')
                ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
                ->orderByRaw('fecha DESC , hora DESC')
                ->limit($constData)
                ->get()
                ->toArray();
        } catch (Exception $e) {
            Log::newError('Traits', 'Max', 'AlertSystem|Traits|StorageServerTrait|calculateA25|No se recuperaron los datos', $e, [$externalConnection, $tableName, $dataOne, $timeOne, $dataTwo, $timeTwo, $constData],true);
            return;
        }
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
        try {

            return DB::connection($externalConnection)
                ->table($tableName)
                ->select('fecha', 'hora', 'precipitacion_real')
                ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
                ->orderByRaw('fecha DESC , hora DESC')
                ->limit($constData)
                ->get()
                ->toArray();
        } catch (Exception $e) {
            Log::newError('Traits', 'Max', 'AlertSystem|Traits|StorageServerTrait|calculateA10|No se recuperaron los datos', $e, [$externalConnection, $tableName, $dataOne, $timeOne, $dataTwo, $timeTwo, $constData],true);
            return;
        }
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
        try {
            $value = DB::connection($externalConnection)
                ->table($tableName)
                ->selectRaw("COUNT($variable) as count")
                ->where($variable, '!=', '-')->whereNotNull($variable)
                ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
                ->first();
            return is_null($value) ? 0 : $value->count;

        } catch (Exception $e) {
            Log::newError('Traits', 'Max', 'AlertSystem|Traits|StorageServerTrait|countDataToExtract|No se recuperaron los datos', $e, [$externalConnection, $tableName, $dataOne, $timeOne, $dataTwo, $timeTwo],true);
            return 0;
        }

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
    )
    {
        try {
            return DB::connection($externalConnection)
                ->table($tableName)
                ->select('fecha', 'hora', $variable) #  TODO incluir nivel para las de inundacion nivel
                ->where($variable, '!=', '-')
                ->whereRaw("((( fecha = '$dataOne' and hora >= '$timeOne') or ( fecha > '$dataOne')) and ((fecha < '$dataTwo') or ( fecha = '$dataTwo' and hora <= '$timeTwo')))")
                ->orderByRaw('fecha DESC , hora DESC')
                ->get()
                ->toArray();
        } catch (Exception $e) {
            Log::newError('Traits', 'Max', 'AlertSystem|Traits|StorageServerTrait|getExternalData|No se recuperaron los datos', $e, [$externalConnection, $tableName, $dataOne, $timeOne, $dataTwo, $timeTwo],true);
            return;
        }
    }
}