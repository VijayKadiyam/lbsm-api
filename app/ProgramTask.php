<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramTask extends Model
{
    protected $fillable = [
        'site_id',
        'program_id',
        'program_post_id',
        'serial_no',
        'task',
        'objective',
        'material',
        'process',
        'no_of_contracts',
        'time_required',
        'total_marks',
        'passing_marks',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
