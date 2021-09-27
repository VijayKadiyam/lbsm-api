<?php

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'caption' => 'caption',
        'moment_id'=>'1',
        'event_id'=>'1',
        'event_date'=>'event_date',
        'posted_by'=>'1',
        'visibility'=>'0',
    ];
});
