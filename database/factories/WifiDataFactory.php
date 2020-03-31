<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Datamining\WifiData;
use Faker\Generator as Faker;

$factory->define(WifiData::class, function (Faker $faker) {
    return [
        'timestamp' => $faker->dateTimeBetween('-1 day', 'now'),
        'SSID' => $faker->word(),
        'BSSID' => $faker->macAddress,
        'RSSI' => $faker->numberBetween(-40, 100),
    ];
});
