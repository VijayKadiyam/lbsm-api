<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgramPost extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'program_post_id',
        'promotion_date',
        'active',
        'remarks',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function program_post()
    {
        return $this->belongsTo(ProgramPost::class)
            ->with('post');
    }
}
