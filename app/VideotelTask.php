<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideotelTask extends Model
{
    protected $fillable = [
        'site_id',
        'ship_id',
        'user_id',
        'training_title',
        'module',
        'type',
        'date',
        'duration',
        'score',
        'is_deleted',
        'active',
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
