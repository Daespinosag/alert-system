<?php


namespace App\AlertSystem\ControlAlert;
use Carbon\Carbon;

interface ControlAlertContract
{
    public function __construct(Carbon $dateTime);
    public function config();
    public function execute();
    public function sendDataToEvent();
    public function formatDataToEvent() : array;
    public function sendEmailAndMsm($data);
}