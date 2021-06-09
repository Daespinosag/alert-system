<?php

namespace App\AlertSystem\Homogenization;

use App\Repositories\AlertSystem\LogsRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use phpDocumentor\Reflection\DocBlock\Description;


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
    public function __construct(Carbon $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function execute(array $recoveryData = [], string $variable = 'precipitacion_real')
    {
        # Se calcula el valor de tiempo numerico para la fecha a homogenizar
        $t3 = Carbon::parse($this->dateTime)->getTimestamp();

        # Se prepran los datos con las marcas de tiempo
        $preProcessData = $this->prepareData($recoveryData);

        # Se valida si la estacion reporto los datos en la fecha de homogenizacion
        $validation = array_search($t3, array_column($preProcessData, 'timeNumber'));
        # Se valida si se encontro el valor en el array entrante
        if (!is_bool($validation)) {

            # Si se encontro se asigna al dato de respuesta
            $this->data = (object)$preProcessData[$validation];

            # Se calcula la marcha de fecha y tiempo
            $this->data->dateTime = $this->dateTime->format('Y-m-d H:i:s');

            # La validacion de los datos es aceptada
            $this->validateHomogenization = true;

            return $this;
        }

        # Se ordenan los valores de menos a mayor en dateTime numerico
        $preparedData = $this->selectBestOptions($preProcessData, $t3);

        # Validar si los datos preparados corresponden a lo esperado
        if (count($preparedData) == 0) {
            return $this;
        }


        $this->validateHomogenization = true;
        # Se realiza la Homogenizacion
        $this->homogenization($preparedData[0], $preparedData[1], $variable, $t3);

        return $this;
    }

    /**
     * @param array $recoveryData
     * @return array
     */
    public function prepareData(array $recoveryData = []): array
    {
        foreach ($recoveryData as $data) {
            $data->timeNumber = Carbon::parse($data->fecha . " " . $data->hora)->getTimestamp();
        }
        return $recoveryData;
    }

    /**
     * @param array $recoveryData
     * @param int $t3
     * @return array
     */
    public function selectBestOptions(array $recoveryData = [], int $t3): array
    {
        #Se inserta el valor a buscar en el array
        $recoveryData[] = (object)['timeNumber' => $t3];

        #Se ordena el array de menor a mayor
        $sortedRecoveryData = $this->orderRecoveryData($recoveryData, 'timeNumber');

        # Se extrae la posición del valor a homogenizar en el array
        $val = array_search($t3, array_column($sortedRecoveryData, 'timeNumber'));
        # Cundo el valor a homogenizar no se encuenta, no es posible homogenizar
        if (is_bool($val)) {
            return [];
        }

        # En caso de que no se encuentre el valor siguiente no se puede realizar la homogenizacion
        if ($val >= count($sortedRecoveryData) - 1) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'Homogenization';
            $log->type = 'Fallo';
            $log->status = 'Active';
            $log->priority = 'Med';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Homogenization|Homogenization|selectBestOptions|No hay dato siguiente';
            $log->aditionalData = json_encode([
                'exeptionMessage' => '',
                'parametersIn' => json_encode([
                    $recoveryData,
                    $t3
                ])
            ]);
            $log->save();

            return [];
        }

        # En caso de que no se encuentre el valor anterior no se puede realizar la homogenizacion
        if ($val <= 0) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'Homogenization';
            $log->type = 'Fallo';
            $log->status = 'Active';
            $log->priority = 'Med';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Homogenization|Homogenization|selectBestOptions|No hay dato anterior';
            $log->aditionalData = json_encode([
                'exeptionMessage' => '',
                'parametersIn' => json_encode([
                    $recoveryData,
                    $t3
                ])
            ]);
            $log->save();

            return [];
        }

        # Se retorna en la posicion uno el valor anterior y en la posicion dos el valor siguiente
        return [$sortedRecoveryData[$val - 1], $sortedRecoveryData[$val + 1]];
    }

    /**
     * @param $firstValue
     * @param $secondValue
     * @param string $variable
     * @param int $t3
     */
    public function homogenization($firstValue, $secondValue, string $variable, int $t3)
    {
        $this->data = (object)[
            'fecha' => $this->dateTime->format('Y-m-d'),
            'hora' => $this->dateTime->format('H:i:s'),
            'dateTime' => $this->dateTime->format('Y-m-d H:i:s'),
            $variable => $this->formula($firstValue->{$variable}, $secondValue->{$variable}, $firstValue->timeNumber, $secondValue->timeNumber, $t3)
        ];
    }

    /**
     * @param array $recoveryData
     * @param string $column
     * @return array
     */
    public function orderRecoveryData(array $recoveryData = [], string $column): array
    {
        return array_values(Arr::sort($recoveryData, function ($value) use ($column) {
            return ((array)$value)[$column];
        }));
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
    private function formula(float $v1, float $v2, int $t1, int $t2, int $t3, int $round = 2): float
    {
        return round(($v1 + (($v2 - $v1) / ($t2 - $t1)) * ($t3 - $t1)), $round);
    }
}