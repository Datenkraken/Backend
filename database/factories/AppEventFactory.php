<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Datamining\AppEvent;
use Faker\Generator as Faker;

$factory->define(AppEvent::class, function (Faker $faker) {
    return [
        'timestamp' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null),
		'type' => $faker->randomElement(['scroll', 'background', 'started', 'closed']),
    ];
});
