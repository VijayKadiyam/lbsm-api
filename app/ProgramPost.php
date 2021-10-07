<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramPost extends Model
{
    protected $fillable = [
        'program_id',
        'serial_no',
        'post_id',
    ];

    public function site() {
        return $this->belongsTo(Site::class);
    }
}
