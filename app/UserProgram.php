<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgram extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',
        'enrollment_date',
    ];

    public function site(){
        return $this->belongsTo(Site::class);
    }
}
