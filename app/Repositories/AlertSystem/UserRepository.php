<?php

namespace App\Repositories\AlertSystem;

use App\Repositories\RepositoriesContract;
use Rinvex\Repository\Repositories\EloquentRepository;
use App\Entities\AlertSystem\User;
use DB;

class UserRepository extends EloquentRepository implements RepositoriesContract
{
    /**
     * @var string
     */
    protected $repositoryId = 'rinvex.repository.uniqueid';
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * @return mixed
     */
    protected function queryBuilder()
    {
        return DB::connection('alert-system')->table('users');
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function getUserFromConfirmationCode(string $code) : User
    {
        return $this->select('*')->where('confirmed_code', $code)->where('confirmed', false)->first();
    }

    /**
     * @param User $user
     * @return User
     */
    public function confirmedUser(User $user) : User
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
     * @return mixed
     */
    public function updateFromEmail(string $email, array $updates = [])
    {
        return $this->queryBuilder()->where('email',$email)->update($updates);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUser(int $id) : User
    {
        return $this->select('*')->where('id',$id)->first();
    }

    /**
     * @param int $id
     * @return User
     */
    public function getCompleteUser(int $id) : User
    {
        return $this->select('*')->where('id',$id)->with('permissions')->first();
    }

    /**
     * @param $alertCode
     * @return array
     */
    public function getEmailUserFromAlert($alertCode) : array
    {
        $val =  $this->queryBuilder()
                    ->select('users.email')
                    ->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
                    ->join('permission', 'permission.id', '=', 'user_permissions.permission_id')
                    ->where('users.confirmed',true)
                    ->where('users.accepted',true)
                    ->where('user_permissions.active',true)
                    ->where('user_permissions.active_email',true)
                    ->where('permission.code','permission-'.$alertCode)
                    ->get();

        return (!is_null($val)) ? $val->toArray() : [];

    }
}