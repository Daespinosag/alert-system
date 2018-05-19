<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $connection = 'administrator';

    protected $table = 'connection';

    protected $primaryKey = 'id';

    protected $fillable = [
        'net_id','name','host','port','database','username','password','connection_driver','rt_active','etl_active'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function net()
    {
        return $this->hasMany(Net::class,'connection_id');
    }
}
