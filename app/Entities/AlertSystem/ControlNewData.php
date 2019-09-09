<?php

namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;

class ControlNewData extends Model
{
    /**
     * @var string
     */
    protected $connection = 'alert-system';
    /**
     * @var string
     */
    protected $table = 'control_new_data';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var array
     */
    protected $fillable = [
        'id','alert_id','alert_code','date','time','active','homogenization'
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