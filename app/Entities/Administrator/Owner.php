<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $connection = 'administrator';

    protected $table = 'owner';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','code','description'
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
    public function stations()
    {
        return $this->belongsToMany(Station::class,'owner_station','owner_id','station_id')
            ->withPivot(['id'])
            ->withTimestamps();
    }
}