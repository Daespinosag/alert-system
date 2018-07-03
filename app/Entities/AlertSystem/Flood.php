<?php

namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;

class Flood extends Model
{
    protected $connection = 'alert-system';

    protected $table = 'flood';

    protected $primaryKey = 'id';

    protected $fillable = [
        'station','a10_value','alert','dif_previous_a10','num_not_change_alert','change_alert','alert_decrease','alert_increase','avg_recovered'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}