<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostHashtag extends Model
{
    protected $fillable = [
        'post_id',
        'hashtag',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
