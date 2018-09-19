<?php
/**
 * Created by PhpStorm.
 * User: Mayordan
 * Date: 28/06/2017
 * Time: 5:10 PM
 */

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\Maintenance;

class MaintenanceRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = Maintenance::class;
}