<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $connection = 'administrator';

    protected $table = 'map';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','description','initial_zoom', 'initial_latitude_degrees','initial_latitude_minutes','initial_latitude_seconds',
        'center_latitude_direction', 'center_longitude_degrees', 'center_longitude_minutes', 'center_longitude_seconds',
        'center_longitude_direction', 'rt_active'
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
    public function maps()
    {
        return $this->belongsToMany(Net::class,'net_map','map_id','net_id')
            ->withPivot(['id','rt_active','rt_default_active'])
            ->withTimestamps();
    }
}
