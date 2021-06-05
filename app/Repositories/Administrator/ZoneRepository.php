<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\Zone;
use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Support\Collection;
use DB;
use Rinvex\Repository\Repositories\EloquentRepository;

class ZoneRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Zone::class;


    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        try {
            return DB::connection('administrator')->table('zone');
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'ZoneRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|ZoneRepository|queryBuilder|No pudo conectarse';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @param array $zones
     * @return Collection
     */
    public function getZonesById(array $zones): Collection
    {
        try {
            return $this->queryBuilder()->select('id', 'name', 'code', 'description', 'kml')->whereIn('id', $zones)->get();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'ZoneRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|Administrator|ZoneRepository|getZonesById|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $zones
                ])
            ]);
            $log->save();
            return;
        }
    }
}