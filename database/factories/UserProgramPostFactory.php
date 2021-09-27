<?php

use App\UserProgramPost;
use Faker\Generator as Faker;

$factory->define(UserProgramPost::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'program_id' => 1,
        'program_post_id' => 1,
        'promote_date' => 'Promote Date',
    ];
});
