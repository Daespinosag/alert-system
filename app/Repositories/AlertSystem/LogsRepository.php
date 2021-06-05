<?php

namespace App\Repositories\AlertSystem;

use App\Mail\LogMail;
use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Logs;
use Carbon\Carbon;

class LogsRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Logs::class;

    public function newObject()
    {
        return new Logs();
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function getAll()
    {
        try {
            return $this->select('*')->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'LogsRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|LogsRepository|getAll|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function isSendEmail($code, $type, $date)
    {
        try {
            return count($this->select('*')
                ->where('code', '=', $code)
                ->where('type', '=', $type)
                ->whereBetween('date', [date_add(clone($date), date_interval_create_from_date_string('-60 minutes'))->format('Y-m-d H:i:s'), (clone($date))->format('Y-m-d H:i:s')])
                ->get()) > 0 ? false : true;
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'LogsRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|LogsRepository|isSendEmail|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $code,
                    $type,
                    $date
                ])
            ]);
            $log->save();
            return;
        }
    }

    public function sendEmail($data)
    {
        if ($this->isSendEmail($data->code, $data->type, $data->date))
            \Mail::to('ideaalertas@gmail.com')->bcc(['ideaalertas@gmail.com'])->send(new LogMail($data->type . ' Detectado Prioridad ' . $data->priority, $data, 'Anomalía Detectada - Revise Logs'));
    }
}