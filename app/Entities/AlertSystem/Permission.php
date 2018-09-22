<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'alert-system';

    protected $table = 'permission';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'description','type'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

}