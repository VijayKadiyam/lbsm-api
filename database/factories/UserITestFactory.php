<?php

use App\UserITest;
use Faker\Generator as Faker;

$factory->define(UserITest::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'date' => 'date',
        'percentage' => 10.00,
        'status' => 'status',
    ];
});
