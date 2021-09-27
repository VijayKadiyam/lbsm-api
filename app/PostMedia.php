<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $fillable = [
        'post_id',
        'media_path',
        'media_caption',
        'visibilty',
    ];


    public function post(){
        return $this->belongsTo(Post::class);
    }
    
    public function post_media_user_visibilities() {
        return $this->hasMany(PostMediaUserVisibility::class);
    }
    public function post_media_relation_visibilities() {
        return $this->hasMany(PostMediaRelationVisibility::class);
    }
}
