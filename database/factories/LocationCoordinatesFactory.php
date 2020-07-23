<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Datamining\LocationCoordinates;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(LocationCoordinates::class, function (Faker $faker) {
    $locations = array(
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.875511,
            'longitude' => 8.656495
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.878122,
            'longitude' => 8.654013
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.871881,
            'longitude' => 8.651596
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.861071,
            'longitude' => 8.682422
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.866206,
            'longitude' => 8.645368
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.872336,
            'longitude' => 8.6439
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.872517,
            'longitude' => 8.630883
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.877505,
            'longitude' => 8.661968
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.819908,
            'longitude' => 8.646746
        ],
        [
            'timestamp' => $faker->dateTimeBetween($startDate = '-14 days', $endDate = '-13 days', $timezone = null),
            'latitude' => 49.881413,
            'longitude' => 8.669101
        ]
    );

    $user = $faker->randomElement(User::all()->toArray());
    $visitedLocation = LocationCoordinates::where('user_id', $user['_id'])->orderBy('timestamp', 'desc')->take(2)->get();
    if (count($visitedLocation) == 0) {
        $lastLocation = $faker->randomElement($locations);
    } else {
        $lastLocation = $visitedLocation[0];
    }

    if (count($visitedLocation) >= 2 && $faker->boolean(85)) {
        $preLastLocation = $visitedLocation[1];
        $longitude = $lastLocation['longitude'] + ($lastLocation['longitude'] - $preLastLocation['longitude']);
        $latitude = $lastLocation['latitude'] + ($lastLocation['latitude'] - $preLastLocation['latitude']);
    } else {
        $longitude = $lastLocation['longitude'] + (rand(0,1) == 0 ? 0.001 : -0.001);
        $latitude = $lastLocation['latitude'] + (rand(0,1) == 0 ? 0.001 : -0.001);
    }

    return [
        'timestamp' => DateTime::createFromFormat('U', ($lastLocation['timestamp']->getTimestamp() + (60 * 15))),
        'user_id' => $user['_id'],
        'altitude' => $faker->numberBetween(-10, 100),
        'accuracy' => $faker->numberBetween(20, 80),
        'longitude' => $longitude,
        'latitude' => $latitude,
        'provider' => 'faker!'
    ];
});
