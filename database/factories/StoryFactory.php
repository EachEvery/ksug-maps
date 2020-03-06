<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use KSUGMap\Story;

$factory->define(Story::class, function (Faker $faker) {
    return [
        'subject' => $faker->name(),
        'role' => $faker->randomElement(['May 2', 'May 3', 'May 4', 'May 5']),
        'day' => $faker->randomElement(['May 2', 'May 3', 'May 4', 'May 5']),
        'audio' => '',
        'place_id' => '',
        'content' => $faker->paragraph(),
    ];
});
