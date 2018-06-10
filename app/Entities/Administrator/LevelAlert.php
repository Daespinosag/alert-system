<?php
/**
 * Created by PhpStorm.
 * User: Mayordan
 * Date: 30/05/2018
 * Time: 9:06 PM
 */

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;


class LevelAlert extends Model
{
    protected $connection = 'administrator';

    protected $table = 'level_alert';

    protected $primaryKey = 'id';

    protected $fillable = [
        'alert_id','name','code','description','level','maximum','minimum'
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
    public function alert()
    {
        return $this->belongsTo(Alert::class,'alert_id');
    }
}