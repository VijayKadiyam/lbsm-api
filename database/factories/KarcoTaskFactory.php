<?php

use App\KarcoTask;
use Faker\Generator as Faker;

$factory->define(KarcoTask::class, function (Faker $faker) {
    return [
        'department' => 'department',
        'status' =>        'status',
        'signed_on' =>        'signed_on',
        'video_title' =>        'video_title',
        'no_of_preview_watched' =>        'no_of_preview_watched',
        'no_of_video_watched' =>        'no_of_video_watched',
        'obtained_marks' =>        'obtained_marks',
        'total_marks' =>        'total_marks',
        'percentage' =>        'percentage',
        'done_on' =>        'done_on',
        'due_days' =>        'due_days',
        'assessment_status' =>        'assessment_status',
    ];
});
