<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrudeUser extends Model
{
    protected $fillable = [
        'site_id',
        'nationality',
        'rank',
        'first_name',
        'middle_name',
        'last_name',
        'danaos_id',
        'dob',
    ];
}
