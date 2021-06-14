<?php

namespace App\AlertSystem\Extract;

use App\Repositories\AlertSystem\LogsRepository;
use Carbon\Carbon;

class AcquisitionServerExtract extends ExtractBase implements ExtractContract
{
    /**
     * AcquisitionServerExtract constructor.
     * @param string $connection
     * @param string $stationTable
     * @param Carbon $initDateTime
     * @param Carbon $finalDateTime
     */
    public function __construct(string $connection, string $stationTable, Carbon $initDateTime, Carbon $finalDateTime)
    {
        parent::__construct($connection, $stationTable, $initDateTime, $finalDateTime);
    }

    public function execute(string $variable)
    {
        #se valida si exite la conexión
        if (is_bool($this->connection)) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AcquisitionServerExtract';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|AlertsV2|Extract|AcquisitionServerExtract|execute|No hay conexión para la estación';
            $log->aditionalData = json_encode([
                'exeptionMessage' => '',
                'parametersIn' => json_encode([
                    $variable
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();
            return $this;
        }
        # Se realiza la validacion de la existencia de los datos en la central de acopio
        $this->validateTheLastData($variable);

        # Si no existe datos en la central de acopio se termina el proceso
        if (!$this->dataExistence) {
            return $this;
        }

        # Se extren los datos de la central de acopio
        $this->extractData($variable);

        return $this;
    }
}