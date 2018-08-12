<?php

namespace App\AlertSystem\Alerts;

use Carbon\Carbon;

interface AlertInterface
{
    /**
     * @return mixed
     */
    public function init();

    /**
     * @param Carbon $initialDate
     * @param Carbon $finalDate
     */
    public function configureDatesToSearch(Carbon $initialDate, Carbon $finalDate);
}

