<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $connection = 'administrator';

    protected $table = 'station';

    protected $primaryKey = 'id';

    protected $fillable = [
        'station_type_id','net_id','code','name','description','table_db_name','measurements_per_day','active',
        'rt_active','etl_active','community','start_operation','finish_operation','latitude_degrees','latitude_minutes',
        'latitude_seconds','latitude_direction','longitude_degrees','longitude_minutes','longitude_seconds','longitude_direction',
        'city','localization','basin','sub_basin','image_1','image_2','pdf_file','comment'
    ];

    protected $hidden = [
        'id',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nets()
    {
        return $this->belongsToMany(Net::class,'station_net','station_id','net_id')
            ->withPivot(['id','rt_active'])
            ->withTimestamps();
    }

    public function net()
    {
        return $this->belongsTo(Net::class,'net_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeStation()
    {
        return $this->belongsTo(StationType::class,'station_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technicalSheetFields()
    {
        return $this->belongsToMany(TechnicalSheetField::class,'technical_sheet_field_station','station_id', 'technical_sheet_field_id')
                    ->withPivot(['id','rt_active','value'])
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function variables()
    {
        return $this->belongsToMany(Variable::class,'variable_station','station_id','variable_id')
                    ->withPivot(['id','maximum','minimum','previous_deference','correction_type','rt_active','etl_active','comment'])
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function graphs()
    {
        return $this->belongsToMany(Graph::class,'graph_station','station_id','graph_id')
                    ->withPivot(['id','rt_active'])
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class,'station_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function originalState()
    {
        return $this->hasOne(OriginalState::class,'station_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function filterState()
    {
        return $this->hasOne(FilterState::class,'station_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function alerts()
    {
        return $this->belongsToMany(Alert::class,'alert_station','station_id','alert_id')
            ->withPivot(['id','active'])
            ->withTimestamps();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function owners()
    {
        return $this->belongsToMany(Owner::class,'owner_station','station_id','owner_id')
            ->withPivot(['id'])
            ->withTimestamps();
    }
}
