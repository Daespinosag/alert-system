<?php

namespace App\Repositories\AlertSystem;

use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\User;
use DB;

class UserRepository extends EloquentRepository
{
    protected $repositoryId = 'rinvex.repository.uniqueid';
    protected $model = User::class;

    /**
     * @return DB
     */
    protected function queryBuilder()
    {
        return DB::connection('alert-system')->table('users');
    }

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

    /**
     * @param string $email
     * @return mixed
     */
    public function validateEmailExistence(string $email)
    {
        return $this->select('*')->where('email', $email)->first();
    }

    /**
     * @param string $email
     * @param array $updates
     * @return
     */
    public function updateFromEmail(string $email, array $updates = [])
    {
        return $this->queryBuilder()->where('email',$email)->update($updates);
    }

    public function getUser($id)
    {
        return $this->select('*')->where('id',$id)->first();
    }
}