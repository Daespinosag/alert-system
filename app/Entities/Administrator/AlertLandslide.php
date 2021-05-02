<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AlertLandslide extends Model
{
    /**
     * @var string
     */
    protected $connection = 'administrator';

    /**
     * @var string
     */
    protected $table = 'alert_landslide';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'id','zone_id','name','code','active','limit_yellow','limit_orange','limit_red','icon'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function zones() : BelongsTo
    {
        return $this->belongsTo(Zone::class,'zone_id');
    }

    /**
     * @return BelongsToMany
     */
    public function stations() : BelongsToMany
    {
        return $this->belongsToMany(Station::class,'station_landslide_alert','landslide_alert_id','station_id')
            ->withPivot(['id','primary','active','distance'])
            ->withTimestamps();
    }
}
