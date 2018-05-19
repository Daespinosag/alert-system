<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $connection = 'administrator';

    protected $table = 'equipment';

    protected $primaryKey = 'id';

    protected $fillable = [
        'equipment_category_id','name','description'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class,'equipment_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maintenances()
    {
        return $this->hasMany(EquipmentMaintenance::class,'equipment_id','id');
    }
}
