<?php

use App\PostMedia;
use Faker\Generator as Faker;

$factory->define(PostMedia::class, function (Faker $faker) {
    return [
        'media_path'=>'media_path',
        'media_caption'=>'media_caption',
        'visibilty'=>'0',
    ];
});
