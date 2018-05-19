<?php

namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;

class A25FiveMinutes extends Model
{
    protected $connection = 'alert-system';

    protected $table = 'a25_five_minutes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'station','a25_value','alert','dif_previous_a25','num_not_change_alert','change_alert','alert_decrease','alert_increase','avg_recovered'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
