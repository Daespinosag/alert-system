<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $connection = 'administrator';

    protected $table = 'maintenance';

    protected $primaryKey = 'id';

    protected $fillable = [
        'station_id','scheduled_date','maintenance_date','state','comment','maintenance_type','creation_date'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at','creation_date','maintenance_date','scheduled_date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station()
    {
        return $this->belongsTo(Station::class,'station_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipments()
    {
        return $this->hasMany(EquipmentMaintenance::class,'maintenance_id','id');
    }
}
