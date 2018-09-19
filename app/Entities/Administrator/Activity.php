<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $connection = 'administrator';

    protected $table = 'activity';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','description'
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
    public function activityEquipmentMaintenances()
    {
        return $this->belongsToMany(EquipmentMaintenance::class,'activity_equipment_maintenance','equipment_maintenance_id','activity_id')
                    ->withTimestamps();
    }
}
