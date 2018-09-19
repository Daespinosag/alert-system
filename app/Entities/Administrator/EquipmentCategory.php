<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    protected $connection = 'administrator';

    protected $table = 'equipment_category';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipments()
    {
        return $this->hasMany(EquipmentCategory::class,'equipment_category_id');
    }
}
