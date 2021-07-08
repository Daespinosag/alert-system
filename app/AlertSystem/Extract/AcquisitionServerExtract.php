<?php

namespace App\AlertSystem\Extract;

use App\Helpers\Log;
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
            Log::newError('AcquisitionServerExtract', 'Max', 'AlertSystem|AlertsV2|Extract|AcquisitionServerExtract|execute|No hay conexión para la estación', '', [$variable],true);
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