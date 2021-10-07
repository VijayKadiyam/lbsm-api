<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProgramTaskDocument extends Model
{
    protected $fillable = [
        'user_program_task_id',
        'document_path',
        'description',
    ];

    public function site() {
        return $this->belongsTo(Site::class);
    }
}
