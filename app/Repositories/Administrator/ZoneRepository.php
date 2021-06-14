<?php


namespace App\Repositories\Administrator;

use App\Entities\Administrator\Zone;
use App\Repositories\AlertSystem\LogsRepository;
use App\Repositories\RepositoriesContract;
use Illuminate\Support\Collection;
use DB;
use App\Repositories\EloquentRepository;

class ZoneRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'app.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = Zone::class;


    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        return DB::connection('administrator')->table('zone');
    }

    /**
     * @param array $zones
     * @return Collection
     */
    public function getZonesById(array $zones): Collection
    {
        return $this->queryBuilder()->select('id', 'name', 'code', 'description', 'kml')->whereIn('id', $zones)->get();
    }
}