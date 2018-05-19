<?php

namespace App\Entities\Administrator;

use Illuminate\Database\Eloquent\Model;

class InformationRequest extends Model
{
    protected $connection = 'administrator';

    protected $table = 'information_request';

    protected $primaryKey = 'id';

    protected $fillable = [
        'comment','subject','information_use','purpose','creation_date','answer_date','petitioner_name','petitioner_email',
        'petitioner_entity','petitioner_phone','petitioner_occupation','petitioner_profession'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dates = [
        'created_at', 'updated_at','creation_date','answer_date'
    ];
}
