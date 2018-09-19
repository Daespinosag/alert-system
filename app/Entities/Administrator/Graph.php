<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Graph extends Model
{
    protected $connection = 'administrator';

    protected $table = 'graph';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','description','graph_file_name'
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
    public function graphs()
    {
        return $this->belongsToMany(Station::class,'graph_station','station_id','graph_id')
            ->withPivot(['id','rt_active'])
            ->withTimestamps();
    }
}
