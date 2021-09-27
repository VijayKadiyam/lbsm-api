<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgramPost extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'program_post_id',
        'promote_date',
    ];

    public function site() {
        return $this->belongsTo(Site::class);
    }
}
