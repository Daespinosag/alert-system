<?php

namespace App\AlertSystem\Traits;

use App\Repositories\AlertSystem\LogsRepository;
use Exception;
use function Couchbase\defaultDecoder;
use DB;
use Carbon\Carbon;

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
        }catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'Traits';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Traits|StorageServerTrait|calculateA25|No se recuperaron los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $externalConnection,
                    $tableName,
                    $dataOne,
                    $timeOne,
                    $dataTwo,
                    $timeTwo,
                    $constData
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();

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
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'Traits';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Traits|StorageServerTrait|calculateA10|No se recuperaron los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $externalConnection,
                    $tableName,
                    $dataOne,
                    $timeOne,
                    $dataTwo,
                    $timeTwo,
                    $constData
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();

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
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'Traits';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Traits|StorageServerTrait|countDataToExtract|No se recuperaron los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $externalConnection,
                    $tableName,
                    $variable,
                    $dataOne,
                    $timeOne,
                    $dataTwo,
                    $timeTwo
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();

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
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Traits|StorageServerTrait|getExternalData|No se recuperaron los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $externalConnection,
                    $tableName,
                    $variable,
                    $dataOne,
                    $timeOne,
                    $dataTwo,
                    $timeTwo
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();
            return;
        }
    }
}