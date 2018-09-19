<?php

namespace App\AlertSystem\Connection;

use Carbon\Carbon;
use DB;

class Query
{
    public $query = null;
    public $data = null;
    private $init = false;
    private $select = false;
    /**
     * @param string $connection
     * @param string $table
     * @return $this
     */
    public function init(string $connection, string $table)
    {
        if (!($connection and $table)){
            dd('Los parametros connection y table son obligatorios para la inicializacion de la consulta');
        }
        $this->query = DB::connection($connection)->table($table);
        $this->init = true;
        return $this;
    }
    /**
     * @param $select
     * @return $this
     */
    public function select(string $select)
    {
        if (!$this->init){ dd('Error no se puede hacer un select sin iniciar la consulta'); }
        if (!$select){dd('Error el parametro select es obligatorio');}
        $this->query->select(DB::raw($select));
        $this->select = true;
        return $this;
    }
    /**
     * @param string $keys
     * @param string $initialDate
     * @param string $initialTime
     * @param string $finalDate
     * @param string $finalTime
     * @return $this
     */
    public function externalWhereBetween(string $keys, string $initialDate, string $initialTime, string $finalDate, string $finalTime)
    {
        if (!$this->select){dd('Error no se puede hacer un where sin el select');}
        if (!$keys){dd('Error el parametro key es obligatorio');}
        if (!($initialTime and $initialDate)){dd('Error los parametros initialTime e initialDate son obligatorios');}
        if (!($finalTime and $finalDate)){dd('Error los parametros finalDate e finalTime son obligatorios');}
        $carbonInitial = Carbon::parse($initialDate.' '.$initialTime);
        $carbonFinal = Carbon::parse($finalDate.' '.$finalTime);
        $this->query->whereBetween(DB::raw("concat_ws(' ',".$keys.")"),[$carbonInitial,$carbonFinal]);
        return $this;
    }
    /**
     * @param string $initialDate
     * @param string $initialTime
     * @param string $finalDate
     * @param string $finalTime
     * @return $this
     */
    public function localWhere(string $initialDate, string $initialTime, string $finalDate, string $finalTime)
    {
        if (!$this->select){dd('Error no se puede hacer un where sin el select');}
        if (!($initialTime and $initialDate)){dd('Error los parametros initialTime e initialDate son obligatorios');}
        if (!($finalTime and $finalDate)){dd('Error los parametros finalDate e finalTime son obligatorios');}
        $this->query->where('date_sk', '=', $initialDate);
        $this->query->where('time_sk' ,'>=' ,$initialTime);
        $this->query->orWhere('date_sk' ,'>' ,$initialDate);
        $this->query->where('date_sk', '<' ,$finalDate);
        $this->query->orWhere('date_sk' ,'=' ,$finalDate);
        $this->query->where('time_sk', '<=' ,$finalTime);
        //(((date_sk = $initialDate and time_sk >= $initialTime) or ( date_sk > $initialDate)) and ((date_sk < $finalDate) or (date_sk = $finalDate and time_sk <= $finalTime))"
        return $this;
    }
    /**
     * @param string $keys
     * @return $this
     */
    public function orderBy(string $keys)
    {
        if (!$this->select){dd('Error no se puede hacer un order by sin el select');}
        $this->query->orderby(DB::raw($keys), 'asc');
        return $this;
    }
    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        if (!$this->select){dd('Error no se puede hacer un limit sin el select');}
        if (!is_null($limit)){$this->query->limit($limit);}
        return $this;
    }
    /**
     * @return $this
     */
    public function execute()
    {
        if (!$this->select){dd('Error no se puede hacer un execute sin el select');}
        $this->data = $this->query->get()->all();
        return $this;
    }
}