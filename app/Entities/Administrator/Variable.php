<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $connection = 'administrator';

    protected $table = 'variable';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','description','excel_name','database_field_name','local_name','decimal_precision','unit', 'report_name'
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
    public function stations()
    {
        return $this->belongsToMany(Station::class,'variable_station','station_id','variable_id')
            ->withPivot(['id','maximum','minimum','previous_deference','correction_type','rt_active','etl_active','comment'])
            ->withTimestamps();
    }
}
