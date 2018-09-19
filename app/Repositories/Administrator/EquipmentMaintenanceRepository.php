<?php


namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\EquipmentMaintenance;

class EquipmentMaintenanceRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = EquipmentMaintenance::class;
}