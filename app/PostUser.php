<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostUser extends Model
{
    protected $fillable = [
        'post_id',
        'user_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
