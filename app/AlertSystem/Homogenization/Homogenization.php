<?php

namespace App\AlertSystem\Homogenization;

use Carbon\Carbon;

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

    public function execute(array $recoveryData = []){
        # Se calcula el valor de tiempo numerico para la fecha a homogenizar
        $t3 = Carbon::parse($this->dateTime)->getTimestamp();

        # Se preparan los datos con las marcas de tiempo para la formula
        $preparedData = $this->prepareData($recoveryData);

        # Se valida si hay mas de dos registros y si es asi se envia a seleccionar los dos mas cercanos
        if (count($recoveryData) > 2){ $preparedData = $this->selectBestOptions($preparedData,$t3); }

        # Se realiza la Homogenizacion
        $this->homogenization($preparedData[0],$preparedData[1]);
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

    }

    /**
     * @param array $firstValue
     * @param array $secondValue
     * @return array
     */
    public function homogenization(array $firstValue, array $secondValue) : array {

    }
}