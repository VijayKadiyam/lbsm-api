<?php

use App\CrudeUser;
use Faker\Generator as Faker;

$factory->define(CrudeUser::class, function (Faker $faker) {
    return [
        'site_id' => 'site_id',
        'nationality' => 'nationality',
        'rank' => 'rank',
        'first_name' => 'first_name',
        'middle_name' => 'middle_name',
        'last_name' => 'last_name',
        'danaos_id' => 'danaos_id',
        'dob' => 'dob',
    ];
});
