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
        try {
            return $this->select('*')->where('id', '=', $id)->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertBaseRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|AlertBaseRepository|getAlert|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $id
                ])
            ]);
            $log->save();
            return;
        }
    }
}
