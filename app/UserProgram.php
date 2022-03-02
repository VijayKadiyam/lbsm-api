<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgram extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'active',
        'enrollment_date',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('rank');
    }
    public function program()
    {
        return $this->belongsTo(Program::class)->with('program_tasks');
    }
    public function user_program_tasks()
    {
        return $this->hasMany(UserProgramTask::class)
            ->with('user', 'program', 'program_task', 'user_program', 'ship');
    }
}
