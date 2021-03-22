<?php


namespace App\AlertSystem\ControlAlert;
use Carbon\Carbon;

interface ControlAlertContract
{
    public function __construct(Carbon $dateTime);
    public function config();
    public function execute();
}