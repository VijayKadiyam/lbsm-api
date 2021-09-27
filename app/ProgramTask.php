<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramTask extends Model
{
    protected $fillable = [
        'program_id',
        'program_post_id',
        'serial_no',
        'task',
        'objective',
        'material',
        'process',
        'no_of_contracts',
        'time_required',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
