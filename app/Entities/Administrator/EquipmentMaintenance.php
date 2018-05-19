<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class EquipmentMaintenance extends Model
{
    protected $connection = 'administrator';

    protected $table = 'equipment_maintenance';

    protected $primaryKey = 'id';

    protected $fillable = [
        'maintenance_id', 'equipment_id'
    ];

    protected $hidden = [
        'id',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class,'maintenance_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class,'equipment_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class,'activity_equipment_maintenance','activity_id','equipment_maintenance_id')
                    ->withTimestamps();
    }

}
