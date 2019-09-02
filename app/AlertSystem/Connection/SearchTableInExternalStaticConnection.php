<?php

namespace App\AlertSystem\Connection;

use Facades\App\Repositories\Administrator\ConnectionRepository;
use Exception;
use DB;
use Log;

trait SearchTableInExternalStaticConnection
{
    /**
     * @var string
     */
    private $baseConnection = 'static-external-connection-';

    /**
     * @param string|null $defaultConnection
     * @param string $table
     * @return bool|string
     */
    public function searchStaticConnection(string $defaultConnection = null, string $table)
    {
        $flag = false;

        if (!is_null($defaultConnection)){
            $flag = $this->searchTable($defaultConnection,$table);
        }

        if ($flag === false){ $flag = $this->searchInMultiplesConnection($table); }

        return $flag;
    }

    /**
     * @param $extractTable
     * @return bool|string
     */
    private function searchInMultiplesConnection($extractTable)
    {
        $connections = ConnectionRepository::getStationsNotIn([1]);

        $i = 0;
        $flag = false;
        $response = false;
        $limit = count($connections);
        while ($i < $limit and !$flag){
            $response = $this->searchTable($connections[$i]->name,$extractTable);
            if ($response !== false){ $flag = true;}
            $i ++;
        }
        return $response;
    }

    /**
     * @param string $connection
     * @param string $externalTable
     * @return bool|string
     */
    private function searchTable(string $connection, string $externalTable)
    {
        try {
            $tables = DB::connection($this->baseConnection.$connection)->select('SHOW TABLES');
        } catch (Exception $e) {
            Log::info('Fallo al buscar las tablas en la base de datos');
            return false;
        }

        $arr= [];
        foreach ($tables as $table){array_push($arr,array_values((array)$table)[0]);}

        $flag = array_search($externalTable,$arr);

        if ($flag !== false){ $flag = $this->baseConnection.$connection;}

        return $flag;
    }
}