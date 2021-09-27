<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostRelationVisibility extends Model
{
    protected $fillable = [
        'post_id',
        'relation_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
