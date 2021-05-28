<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\AlertLandslide;
use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;

class AlertLandslideRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = AlertLandslide::class;


    public function getAlerts()
    {
        try {
            return $this->select('id', 'zone_id', 'name', 'code', 'active', 'limit_yellow as limitYellow', 'limit_orange as limitOrange', 'limit_red as limitRed', 'icon')->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'AlertLandslideRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|AlertLandslideRepository|getAlerts|No pudo recuperar las alertas';
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