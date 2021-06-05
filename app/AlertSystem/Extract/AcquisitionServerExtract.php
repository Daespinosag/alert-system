<?php

namespace App\AlertSystem\Extract;

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
    public function __construct(string $connection, string $stationTable, Carbon $initDateTime, Carbon $finalDateTime){
        parent::__construct($connection,$stationTable,$initDateTime,$finalDateTime);
    }

    public function execute(string $variable){
        # Se realiza la validacion de la existencia de los datos en la central de acopio

        $this->validateTheLastData($variable);

        # Si no existe datos en la central de acopio se termina el proceso
        if (!$this->dataExistence){ return $this;}

        # Se extren los datos de la central de acopio
        $this->extractData($variable);

        return $this;
    }
}