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
        try {
            return DB::connection('alert-system')->table('users');

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|queryBuilder|No pudo conectarse';
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
     * @param string $code
     * @return mixed
     */
    public function getUserFromConfirmationCode(string $code): User
    {
        try {
            return $this->select('*')->where('confirmed_code', $code)->where('confirmed', false)->first();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|getUserFromConfirmationCode|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $code
                ])
            ]);
            $log->save();
            return null;
        }
    }

    /**
     * @param User $user
     * @return User
     */
    public function confirmedUser(User $user): User
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
        try {
            return $this->select('*')->where('email', $email)->first();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|validateEmailExistence|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $email
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @param string $email
     * @param array $updates
     * @return mixed
     */
    public function updateFromEmail(string $email, array $updates = [])
    {
        try {
            return $this->queryBuilder()->where('email', $email)->update($updates);

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|updateFromEmail|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $email,
                    $updates
                ])
            ]);
            $log->save();
            return;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUser(int $id): User
    {
        try {
            return $this->select('*')->where('id', $id)->first();
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|getUser|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $id
                ])
            ]);
            $log->save();
            return null;
        }
    }

    /**
     * @param int $id
     * @return User
     */
    public function getCompleteUser(int $id): User
    {
        try {
            return $this->select('*')->where('id', $id)->with('permissions', 'role')->first();

        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|getCompleteUser|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $id
                ])
            ]);
            $log->save();
            return null;
        }
    }

    /**
     * @param $alertCode
     * @return array
     */
    public function getEmailUserFromAlert($alertCode): array
    {
        try {
            $val = $this->queryBuilder()
                ->select('users.email')
                ->join('user_permissions', 'user_permissions.user_id', '=', 'users.id')
                ->join('permission', 'permission.id', '=', 'user_permissions.permission_id')
                ->where('users.confirmed', true)
                ->where('users.accepted', true)
                ->where('user_permissions.active', true)
                ->where('user_permissions.active_email', true)
                ->where('permission.code', 'permission-' . $alertCode)
                ->get();

            return (!is_null($val)) ? $val->toArray() : [];
        } catch (Exception $e) {
            $logRepository = new  LogsRepository();
            $log = $logRepository->newObject();
            $log->code = 'UserRepository';
            $log->type = 'Error';
            $log->status = 'Active';
            $log->priority = 'Max';
            $log->date = Carbon::now();
            $log->comments = 'AlertSystem|Repositories|AlertSystem|UserRepository|getEmailUserFromAlert|No pudo recuperar los datos';
            $log->aditionalData = json_encode([
                'exeptionMessage' => $e,
                'parametersIn' => json_encode([
                    $alertCode
                ])
            ]);
            $log->save();
            return [];
        }
    }
}