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

    public function execute(){
        $this->validateTheLastData();

        if (!$this->dataExistence){ return $this;}

        $this->extractData();

        return $this;
    }
}