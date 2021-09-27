<?php

use App\UserProgramTaskDocument;
use Faker\Generator as Faker;

$factory->define(UserProgramTaskDocument::class, function (Faker $faker) {
    return [
        'user_program_task_id'=>  1,
        'description'=>  'Description',
    ];
});
