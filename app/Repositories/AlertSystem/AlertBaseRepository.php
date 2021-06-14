<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\DB;

class AlertBaseRepository extends EloquentRepository implements RepositoriesContract
{


    /**
     * @param int $id
     * @return mixed
     */
    public function getAlert(int $id)
    {
        return $this->select('*')->where('id', '=', $id)->first();
    }
}
