<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'site_id',
        'caption',
        'moment_id',
        'event_id',
        'event_date',
        'posted_by',
        'visibility',
    ];


    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }

    public function post_users()
    {
        return $this->hasMany(PostUser::class);
    }
    public function post_hashtags()
    {
        return $this->hasMany(PostHashtag::class);
    }
    public function post_user_visibilities()
    {
        return $this->hasMany(PostUserVisibility::class);
    }
    public function post_relation_visibilities()
    {
        return $this->hasMany(PostRelationVisibility::class);
    }

    public function post_medias()
    {
        return $this->hasMany(PostMedia::class)
        ->with('post_media_user_visibilities')
        ->with('post_media_relation_visibilities');
    }
}
