<?php

namespace App\AlertSystem\Traits;

use DB;

trait StorageServerTrait
{

    /**
     * @param string $externalConnection
     * @param string $tableName
     * @param string $dataOne
     * @param string $dataTwo
     * @param string $time
     * @return mixed => el valor A25 y el contador de datos recuperados
     */
    public function calculateA25(
        string $externalConnection,
        string $tableName,
        string $dataOne,
        string $dataTwo,
        string $time
    )
    {
        return  DB::connection($externalConnection)
            ->table($tableName)
            ->select(DB::raw('sum(precipitacion_real) as a25, count(precipitacion_real) as count'))
            ->where('fecha', '=', $dataOne)
            ->where('hora','>=',$time)
            ->orWhere('fecha','>=',$dataOne)
            ->where('fecha','<=',$dataTwo)
            ->orWhere('fecha','=',$dataTwo)
            ->where('hora','<=',$time)
            ->where('precipitacion_real', '<>','-')
            ->first();
    }

    public function calculateA10
    (
        string $externalConnection,
        string $tableName,
        string $dataOne,
        string $timeOne,
        string $dataTwo,
        string $timeTwo
    )
    {
        return  DB::connection($externalConnection)
            ->table($tableName)
            ->select(DB::raw('sum(precipitacion_real) as a10, count(precipitacion_real) as count'))
            ->where('fecha', '=', $dataOne)
            ->where('hora','>=',$timeOne)
            ->where('fecha','=',$dataTwo)
            ->where('hora','<=',$timeTwo)
            ->where('precipitacion_real', '<>','-')
            ->first();
    }

}