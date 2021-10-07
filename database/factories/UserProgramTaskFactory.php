<?php

use App\UserProgramTask;
use Faker\Generator as Faker;

$factory->define(UserProgramTask::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'program_id' => 1,
        'program_task_id' => 1,
        'marks_obtained' => 0,
        'is_completed' => 0,
        'completion_date' => 'completion_date',
    ];
});
