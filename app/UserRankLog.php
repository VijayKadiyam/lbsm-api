<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRankLog extends Model
{
    protected $fillable = [
        'is_active',
        'is_deleted',
        'site_id',
        'rank_id',
        'status',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function rank()
    {
        return $this->belongsTo(ValueList::class);
    }
}
