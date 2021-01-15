<?php


namespace App\Entities\AlertSystem;


use Illuminate\Database\Eloquent\Model;

class TrackingFloodAlert extends Model
{
    /**
     * @var string
     */
    protected $connection = 'alert-system';

    /**
     * @var string
     */
    protected $table = 'tracking_flood_alert';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'sup_id',
        'alert_id',
        'primary_station_id',
        'secondary_calculate',
        'rainfall',
        'water_level',
        'rainfall_recovered',
        'indicator_value',
        'indicator_previous_difference',
        'alert_level',
        'alert_tag',
        'alert_status',
        'date_time_homogenization',
        'date_time_execution',
        'date_time_initial',
        'date_time_final',
        'error',
        'comment'
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
}