<?php

use App\PostHashtag;
use Faker\Generator as Faker;

$factory->define(PostHashtag::class, function (Faker $faker) {
    return [
        // 'post_id'=>'1',
        'hashtag'=>'hashtag',
    ];
});
