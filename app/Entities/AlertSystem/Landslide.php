<?php

namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;

class Landslide extends Model
{
    protected $connection = 'alert-system';

    protected $table = 'landslide';

    protected $primaryKey = 'id';

    protected $fillable = [
        'station',
        'a25_value',
        'alert',
        'dif_previous_a25',
        'num_not_change_alert',
        'change_alert',
        'alert_decrease',
        'alert_increase',
        'avg_recovered',
        'date_execution',
        'date_initial',
        'date_final'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
