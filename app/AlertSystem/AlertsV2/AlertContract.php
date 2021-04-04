<?php


namespace App\AlertSystem\AlertsV2;


interface AlertContract
{
    public function formatDataToEvent() : array;
    public function sendDataToEvent();
}