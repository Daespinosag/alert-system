<?php

namespace App\AlertSystem;

use App\AlertSystem\Connection\{DatabaseConfig,SearchTableInExternalStaticConnection};
use App\AlertSystem\Traits\StorageServerTrait;
use DB;


abstract class AlertSystem
{
    use DatabaseConfig,StorageServerTrait,SearchTableInExternalStaticConnection;

}