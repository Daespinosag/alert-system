<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{

    protected $connection = 'alert-system';

    protected $table = 'user_permissions';

    protected $primaryKey = 'id';

    protected $fillable = [
      'user_id','permission_id','active','active_email',
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}