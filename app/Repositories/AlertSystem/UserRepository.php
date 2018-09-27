<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\User;

class UserRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = User::class;

    /**
     * @param string $code
     * @return mixed
     */
    public function getUserFromConfirmationCode(string $code)
    {
        return $this->select('*')->where('confirmed_code', $code)->where('confirmed', false)->first();
    }

    /**
     * @param User $user
     * @return User
     */
    public function confirmedUser(User $user)
    {
        $user->confirmed = true;
        $user->confirmed_code = null;
        $user->save();

        return $user;
    }
}