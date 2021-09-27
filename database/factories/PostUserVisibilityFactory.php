<?php

use App\PostUserVisibility;
use Faker\Generator as Faker;

$factory->define(PostUserVisibility::class, function (Faker $faker) {
    return [
        // 'post_id' => '1',
        'user_id' => '1',
    ];
});
