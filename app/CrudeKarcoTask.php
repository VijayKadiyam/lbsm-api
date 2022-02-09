<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrudeKarcoTask extends Model
{
    protected $fillable = [
        'site_id',
        'vessel_name',
        'crew_name',
        'employee_id',
        'rank',
        'department',
        'status',
        'signed_on',
        'nationality',
        'video_title',
        'no_of_preview_watched',
        'no_of_video_watched',
        'obtained_marks',
        'total_marks',
        'percentage',
        'done_on',
        'due_days',
        'assessment_status',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
