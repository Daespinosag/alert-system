<?php

namespace App\Entities\AlertSystem;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Entities\AlertSystem\Role;
use App\Entities\AlertSystem\Permission;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'alert-system';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'role_id', 'name', 'institution','email','confirmed_code','confirmed','accepted','active' , 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'user_permissions','user_id','permission_id')
            ->withPivot(['id','active','active_email'])
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}
