<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class TechnicalSheetField extends Model
{
    protected $connection = 'administrator';

    protected $table = 'technical_sheet_field';

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

    public function stations()
    {
        return $this->belongsToMany(Station::class,'technical_sheet_field_station','station_id','technical_sheet_field_id')
                    ->withPivot(['id','rt_active','value'])
                    ->withTimestamps();
    }
}
