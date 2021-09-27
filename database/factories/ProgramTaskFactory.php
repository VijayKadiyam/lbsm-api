<?php

use App\ProgramTask;
use Faker\Generator as Faker;

$factory->define(ProgramTask::class, function (Faker $faker) {
    return [
        'program_id' => 1,
        'program_post_id' => 1,
        'serial_no' => 1,
        'task' => "task",
        'objective' => "objective",
        'material' => "material",
        'process' => "process",
        'no_of_contracts' => 0,
        'time_required' => 0,
    ];
});
