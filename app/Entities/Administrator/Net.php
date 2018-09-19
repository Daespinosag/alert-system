<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Net extends Model
{
    protected $connection = 'administrator';

    protected $table = 'net';

    protected $primaryKey = 'id';

    protected $fillable = [
        'connection_id','name','description','administrator_name', 'center_latitude_degrees','center_latitude_minutes','center_latitude_seconds',
        'center_latitude_direction', 'center_longitude_degrees', 'center_longitude_minutes', 'center_longitude_seconds',
        'center_longitude_direction', 'rt_active', 'etl_active','map_zoom', 'original_updated', 'filtered_updated'
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
        return $this->belongsToMany(Map::class,'net_map','net_id','map_id')
                    ->withPivot(['id','rt_active','rt_default_active'])
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stations()
    {
        return $this->belongsToMany(Station::class,'station_net','net_id','station_id')
            ->withPivot(['id','rt_active'])
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function connection()
    {
        return $this->belongsTo(Connection::class,'connection_id');
    }

}
