<?php

use App\Program;
use Faker\Generator as Faker;

$factory->define(Program::class, function (Faker $faker) {
    return [
        'program_name' => 'Program Name',
        'program_description' => 'Program Description',
        'instructor' => 'Instructor',
        'hours' => 1,
    ];
});
