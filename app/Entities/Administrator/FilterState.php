<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class FilterState extends Model
{
    protected $connection = 'administrator';

    protected $table = 'filter_state';

    protected $primaryKey = 'id';

    protected $fillable = [
        'station_id','current_date','current_time','updated'
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
    public function station()
    {
        return $this->belongsTo(Station::class,'station_id');
    }
}
