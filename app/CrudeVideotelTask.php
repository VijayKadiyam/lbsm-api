<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrudeVideotelTask extends Model
{
    protected $fillable = [
        'site_id',
        'location',
        'first_name',
        'last_name',
        'crew_id',
        'rank',
        'training_title',
        'module',
        'type',
        'date',
        'duration',
        'score',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
