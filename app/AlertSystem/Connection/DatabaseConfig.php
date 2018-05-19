<?php

namespace App\AlertSystem\Connection;

use Facades\App\Repositories\Administrator\ConnectionRepository;
use Config;
use Exception;
use DB;
use Illuminate\Support\Facades\Artisan;
use Log;
/**
 *
 */
trait DatabaseConfig
{
    /**
     * @param $connection
     * @param $extractTable
     * @param string $defaultConnection
     * @return bool
     */
    public function searchExternalConnection($connection, $extractTable = null,$defaultConnection = 'external_connection')
    {
        $var = $this->configExternalConnection($connection,$defaultConnection);
        if (!is_null($extractTable)){
            if ($var){
                if ($this->validateExistenceExternalTable($extractTable,$defaultConnection)){return true;}
            }
            $this->loopForConnection($connection->id,$extractTable,$defaultConnection);
        }
        return true;
    }
    /**
     * @param $connectionId
     * @param $extractTable
     * @param $defaultConnection
     * @return bool
     */
    private function loopForConnection($connectionId, $extractTable,$defaultConnection)
    {
        $connections = ConnectionRepository::getStationsNotIn([$connectionId,1]);
        $i = 0;
        $flag = false;
        $limit = count($connections);
        while ($i < $limit and !$flag){
            $var = $this->configExternalConnection($connections[$i],$defaultConnection);
            if ($var){
                $flag = $this->validateExistenceExternalTable($extractTable,$defaultConnection);
            }
            $i++;
        }
        return $flag;
    }
    /**
     * @param string $extractTable
     * @param $defaultConnection
     * @return bool
     */
    private function validateExistenceExternalTable(string $extractTable,$defaultConnection)
    {
        $tables = DB::connection($defaultConnection)->select('SHOW TABLES');
        $arr= [];
        foreach ($tables as $table){array_push($arr,array_values((array)$table)[0]);}
        return (!(array_search($extractTable,$arr) == false));
    }
    /**
     * Configuration external connection.
     *
     * @param $connection
     * @param $defaultConnection
     * @return bool
     */
      private function configExternalConnection($connection,$defaultConnection)
      {
          DB::disconnect($defaultConnection);
          if ($connection->id == 1){
              return false;
          }
          if (!$connection->etl_active){
                // TODO.. Excepcion por no estar activa la conección.
              Log::info('Coneccion desabilidata para el proceso de etl');
              return false;
          }
        try {

          // Se extrae la contaseña del servidor de adquisicion del archivo de variables de entorno.
          $connection->password = config('app.external_pass');
      
          Config::set("database.connections.".$defaultConnection.".driver", $connection->connection_driver);
          Config::set("database.connections.".$defaultConnection.".host", $connection->host);
          Config::set("database.connections.".$defaultConnection.".port", $connection->port);
          Config::set("database.connections.".$defaultConnection.".database", $connection->database);
          Config::set("database.connections.".$defaultConnection.".username", $connection->username);
          Config::set("database.connections.".$defaultConnection.".password", $connection->password);
          //dd(Config::get('database.connections.'.$defaultConnection));
          return true;
        } catch (Exception $e) {
            dd('fallo la conexion');
          Log::info('Fallo en la conexión.');
          return false;
        }
      }
}