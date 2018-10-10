<?php

namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;

use App\Entities\AlertSystem\User;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Users()
    {
        return $this->belongsToMany(User::class,'user_permissions','permission_id','user_id')
            ->withPivot(['id','active','active_email'])
            ->withTimestamps();
    }

}