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

    public function site() {
        return $this->belongsTo(Site::class);
    }
}
