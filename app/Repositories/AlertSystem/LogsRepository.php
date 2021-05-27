<?php

namespace App\Repositories\AlertSystem;

use App\Mail\LogMail;
use App\Repositories\RepositoriesContract;
use Illuminate\Database\Eloquent\Collection;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\Logs;

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
        return $this->select('*')->get();
    }

    public function sendEmail($data)
    {
        \Mail::to('ideaalertas@gmail.com')->bcc(['ideaalertas@gmail.com'])->send(new LogMail($data->type.' Detectado Prioridad '.$data->priority, $data, 'Anomalía Detectada - Revise Logs'));
    }
}