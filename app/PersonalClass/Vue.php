<?php

namespace App\PersonalClass;

class Vue
{
    /**
     * @param $name
     * @param $value
     * @return string
     */
    public static function prop($name, $value)
    {
        return sprintf(":%s='%s'", $name, json_encode($value));
    }
}