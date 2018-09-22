<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    protected $connection = 'alert-system';

    protected $table = 'role';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'description',
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

}