<?php


namespace App\Entities\AlertSystem;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    /**
     * @var string
     */
    protected $connection = 'alert-system';

    /**
     * @var string
     */
    protected $table = 'logs';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'status',
        'priority',
        'date',
        'comments',
        'aditionalData'
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