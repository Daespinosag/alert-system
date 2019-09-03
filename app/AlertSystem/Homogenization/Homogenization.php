<?php

namespace App\AlertSystem\Homogenization;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class Homogenization extends HomogenizationBase implements HomogenizationContract
{

    /**
     * @var Carbon
     */
    private $dateTime;

    /**
     * @var bool
     */
    public $validateHomogenization = false;

    /**
     * @var array
     */
    public $data = [];

    /**
     * Homogenization constructor.
     * @param Carbon $dateTime
     */
    public function __construct(Carbon $dateTime){
        $this->dateTime = $dateTime;
    }

    public function execute(array $recoveryData = [],string $variable = 'precipitacion_real'){
        # Se calcula el valor de tiempo numerico para la fecha a homogenizar
        $t3 = Carbon::parse($this->dateTime)->getTimestamp();

        # Se ordenan los valores de menos a mayor en dateTime numerico
        $preparedData = $this->selectBestOptions($this->prepareData($recoveryData),$t3);

        # Validar si los datos preparados corresponden a lo esperado
        if (count($preparedData) != 0){$this->validateHomogenization = true;}

        # Se realiza la Homogenizacion
        if ($this->validateHomogenization){$this->homogenization($preparedData[0],$preparedData[1],'precipitacion_real',$t3);}

        return $this;
    }

    /**
     * @param array $recoveryData
     * @return array
     */
    public function prepareData(array $recoveryData = []) : array {
        foreach ($recoveryData as $data){
            $data->timeNumber = Carbon::parse($data->fecha ." ". $data->hora)->getTimestamp();
        }
        return $recoveryData;
    }

    /**
     * @param array $recoveryData
     * @param int $t3
     * @return array
     */
    public function selectBestOptions(array $recoveryData = [],int $t3): array {
        #Se inserta el valor a buscar en el array
        $recoveryData[] = (object)['timeNumber'=> $t3];

        #Se ordena el array de menor a mayor
        $sortedRecoveryData = $this->orderRecoveryData($recoveryData,'timeNumber');

        $bestOptions = [];
        foreach ($sortedRecoveryData as $key => $data){
            if ($data->timeNumber  == $t3){
                if (!($key == 0 or $key == count($sortedRecoveryData))){
                    $bestOptions[] = $sortedRecoveryData[$key - 1];
                    $bestOptions[] = $sortedRecoveryData[$key + 1];
                }
            }
        }

        return $bestOptions;
    }

    /**
     * @param $firstValue
     * @param $secondValue
     * @param string $variable
     * @param int $t3
     */
    public function homogenization($firstValue, $secondValue,string $variable,int $t3){
        $this->data =  (object)[
            'fecha'     => $this->dateTime->format('Y-m-d'),
            'hora'      => $this->dateTime->format('h:i:s'),
            'dateTime'  => $this->dateTime->format('Y-m-d h:i:s'),
            $variable   => $this->formula($firstValue->{$variable},$secondValue->{$variable},$firstValue->timeNumber,$secondValue->timeNumber,$t3)
        ];
    }

    /**
     * @param array $recoveryData
     * @param string $column
     * @return array
     */
    public function orderRecoveryData(array $recoveryData = [],string $column) : array {
        return array_values(Arr::sort($recoveryData, function ($value) use($column) { return ((array)$value)[$column]; }));
    }

    /**
     * @param float $v1
     * @param float $v2
     * @param int $t1
     * @param int $t2
     * @param int $t3
     * @param int $round
     * @return float
     */
    private function formula(float $v1, float $v2, int $t1, int $t2, int $t3, int $round = 2) : float {
        return round(($v1 + (($v2 - $v1)/($t2 - $t1)) * ($t3 - $t1)),$round);
    }
}