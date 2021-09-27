<?php

use App\UserProgram;
use Faker\Generator as Faker;

$factory->define(UserProgram::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'program_id' =>  1,
        'enrollment_date' => 'Enrollment Date',
    ];
});
