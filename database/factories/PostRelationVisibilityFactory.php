<?php

use App\PostRelationVisibility;
use Faker\Generator as Faker;

$factory->define(PostRelationVisibility::class, function (Faker $faker) {
    return [
        'post_id'=>'1',
        'relation_id'=>'1',
    ];
});
