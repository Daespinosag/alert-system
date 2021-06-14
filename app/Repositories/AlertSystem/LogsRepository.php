<?php

namespace App\Repositories\AlertSystem;

use App\Mail\LogMail;
use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Logs;
use Carbon\Carbon;

class LogsRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
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
        return $this->select('*')->get();
    }

    public function isSendEmail($code, $type, $date)
    {
        return count($this->select('*')
            ->where('code', '=', $code)
            ->where('type', '=', $type)
            ->whereBetween('date', [date_add(clone($date), date_interval_create_from_date_string('-60 minutes'))->format('Y-m-d H:i:s'), (clone($date))->format('Y-m-d H:i:s')])
            ->get()) > 0 ? false : true;
    }

    public function sendEmail($data)
    {
        if ($this->isSendEmail($data->code, $data->type, $data->date))
            \Mail::to('ideaalertas@gmail.com')->bcc(['ideaalertas@gmail.com'])->send(new LogMail($data->type . ' Detectado Prioridad ' . $data->priority, $data, 'Anomalía Detectada - Revise Logs'));
    }
}