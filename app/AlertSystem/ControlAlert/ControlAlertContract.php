<?php


namespace App\AlertSystem\ControlAlert;
use Carbon\Carbon;

interface ControlAlertContract
{
    public function __construct(Carbon $dateTime, $config = null);
    public function config();
    public function execute();
    public function sendDataToEvent();
    public function formatDataToEvent();
    public function sendEmailAndMsm($data);
}