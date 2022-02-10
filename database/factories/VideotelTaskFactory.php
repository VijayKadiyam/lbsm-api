<?php

use App\VideotelTask;
use Faker\Generator as Faker;

$factory->define(VideotelTask::class, function (Faker $faker) {
    return [
        'training_title'=>'training_title',
        'module'=>'module',
        'type'=>'type',
        'date'=>'date',
        'duration'=>'duration',
        'score'=>'score',
    ];
});
