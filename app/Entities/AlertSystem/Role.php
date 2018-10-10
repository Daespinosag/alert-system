<?php

namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;
use App\Entities\AlertSystem\User;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function originalState()
    {
        return $this->hasOne(User::class,'user_id','id');
    }

}