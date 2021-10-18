<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgramTask extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'program_task_id',
        'marks_obtained',
        'is_completed',
        'completion_date',
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
    public function user_program()
    {
        return $this->belongsTo(UserProgram::class);
    }
}
