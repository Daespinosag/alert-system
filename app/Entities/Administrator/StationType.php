<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class StationType extends Model
{
    protected $connection = 'administrator';

    protected $table = 'station_type';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','code','etl_method','description','report_name'
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
    public function stations()
    {
        return $this->hasMany(Station::class,'station_type_id','id');
    }
}
