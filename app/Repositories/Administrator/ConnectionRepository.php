<?php

namespace App\Repositories\Administrator;

use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Container\Container;
use App\Entities\Administrator\Connection;
use Rinvex\Repository\Repositories\EloquentRepository;

class ConnectionRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Connection::class;

    /**
     * @param $variables
     * @return mixed
     *
     */
    public function getStationsNotIn($variables)
    {
        try {
            return $this->select('*')->whereNotIn('id', $variables)->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'ConnectionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|ConnectionRepository|getStationsNotIn|No pudo obtener los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $variables
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();
            return;
        }
    }

    /**
     * @return mixed
     */
    public function searchEtlActive()
    {
        try {
            return $this->select('*')->where('etl_active', true)->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'ConnectionRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|ConnectionRepository|searchEtlActive|No pudo obtener los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $logRepository->sendEmail($log);
            $log->save();
            return;
        }
    }
}