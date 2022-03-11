<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KarcoTask extends Model
{
    protected $fillable=[
        'site_id',
        'ship_id',
        'user_id',
        'department',
        'status',
        'signed_on',
        'video_title',
        'no_of_preview_watched',
        'no_of_video_watched',
        'obtained_marks',
        'total_marks',
        'percentage',
        'done_on',
        'due_days',
        'assessment_status',
        'is_deleted',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('rank');
    }

    public function ship()
    {
        return $this->belongsTo(ValueList::class);
    }
}
