<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\DeletedUserRecord;
use Faker\Generator as Faker;

$factory->define(DeletedUserRecord::class, function (Faker $faker) {
    return [
        'timestamp' => $faker->dateTimeBetween('-20 days', 'now', null),
        'account_created_at' => $faker->dateTimeBetween('-40 days', '-20 days', null),
    ];
});
