<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AlertFlood extends Model
{
    /**
     * @var string
     */
    protected $connection = 'administrator';

    /**
     * @var string
     */
    protected $table = 'alert_flood';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $foreignKey = 'alert_flood_id';

    /**
     * @var array
     */
    protected $fillable = [
        'name','code','active','limit_red','icon'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'id'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * @var array
     */
    protected $pivotStation = [
        'table'         => 'station',
        'pivotTable'    => 'station_flood_alert',
        'foreignKey'    => 'station_id',
        'variables'     => ['id','primary','active','distance']
    ];

    /**
     * @var array
     */
    protected $relationBasins = [
        'table'         => 'basin',
        'foreignKey'    => 'basin_id'
    ];

    /**
     * @return BelongsTo
     */
    public function basins() : BelongsTo
    {
        return $this->belongsTo(Basin::class,$this->relationBasins['foreignKey']);
    }

    /**
     * @return BelongsToMany
     */
    public function stations() : BelongsToMany
    {
        return $this->belongsToMany(Station::class,$this->pivotStation['pivotTable'],$this->foreignKey,$this->pivotStation['foreignKey'])->withPivot($this->pivotStation['variables'])->withTimestamps();
    }
}
