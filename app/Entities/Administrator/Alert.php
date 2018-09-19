<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $connection = 'administrator';

    protected $table = 'alert';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','code','icon','table','description','active'
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
        return $this->belongsToMany(Station::class,'alert_station','alert_id','station_id')
            ->withPivot(['id','active','flag_level_one','flag_level_two','flag_level_three'])
            ->withTimestamps();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function originalState()
    {
        return $this->hasOne(LevelAlert::class,'alert_id','id');
    }


}