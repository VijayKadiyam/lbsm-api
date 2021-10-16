<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'program_name',
        'program_description',
        'instructor',
        'hours',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function program_tasks()
    {
        return $this->hasMany(ProgramTask::class)
            ->with('program');
    }
    public function user_programs()
    {
        return $this->hasMany(UserProgram::class);
    }
    public function user_program_tasks()
    {
        return $this->hasMany(UserProgramTask::class);
    }
}
