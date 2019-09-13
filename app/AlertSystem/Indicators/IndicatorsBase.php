<?php

namespace App\AlertSystem\Indicators;

use App\Repositories\RepositoriesContract;

class IndicatorsBase
{
    /**
     * @var
     */
    protected $value;

    /**
     * @var RepositoriesContract
     */
    protected $trackingTableRepository;

    /**
     * IndicatorsBase constructor.
     * @param RepositoriesContract $trackingTableRepository
     * @param $value
     */
    public function __construct(RepositoriesContract $trackingTableRepository,$value){
        $this->trackingTableRepository = $trackingTableRepository;
        $this->value = $value;
    }
}