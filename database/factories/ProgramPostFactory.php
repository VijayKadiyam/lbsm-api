<?php

use App\ProgramPost;
use Faker\Generator as Faker;

$factory->define(ProgramPost::class, function (Faker $faker) {
    return [
        'program_id' => 1,
        'serial_no' => 1,
        'post_id' => 1,
    ];
});
