<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\User;

class UserRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = User::class;
}