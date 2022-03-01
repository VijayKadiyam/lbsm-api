<?php

use App\UserShip;
use Faker\Generator as Faker;

$factory->define(UserShip::class, function (Faker $faker) {
    return [
        'user_id' =>        1,
        'ship_id' =>        1,
        'from_date' =>      'from_date',
        'to_date' =>        'to_date',
    ];
});
