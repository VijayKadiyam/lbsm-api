<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserITest extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'percentage',
        'status',
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
}
