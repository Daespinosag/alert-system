<?php

namespace App\Repositories\Administrator;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\Administrator\InformationRequest;

class InformationRequestRepository extends  EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';

    protected $model = InformationRequest::class;
}