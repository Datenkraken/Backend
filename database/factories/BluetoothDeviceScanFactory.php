<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Datamining\BluetoothDeviceScan;
use Faker\Generator as Faker;

$factory->define(BluetoothDeviceScan::class, function (Faker $faker) {
    $devices = array(
        '00:00:00:00:00:01',
        '00:00:00:00:00:02',
        '00:00:00:00:00:03',
        '00:00:00:00:00:04',
        '00:00:00:00:00:05',
        '00:00:00:00:00:06',
        '00:00:00:00:00:07',
        '00:00:00:00:00:08',
        '00:00:00:00:00:09',
        '00:00:00:00:00:10',
        '00:00:00:00:00:11',
        '00:00:00:00:00:12',
        '00:00:00:00:00:13',
        '00:00:00:00:00:14',
        '00:00:00:00:00:15',
        '00:00:00:00:00:16',
        '00:00:00:00:00:17',
        '00:00:00:00:00:18',
        '00:00:00:00:00:19',
        '00:00:00:00:00:20',
    );

    $device = $faker->randomElement($devices);
    return [
        'timestamp' => $faker->dateTimeBetween('-14 day', 'now'),
        'name' => $faker->name . '\'s mobile phone',
        'address' => $device,
        'known' => false,
    ];
});

$factory->afterCreating(BluetoothDeviceScan::class, function ($scan, $faker) {
    $user = App\Models\User::where('is_admin', false)->get()->random();
    $user->dataBluetoothScans()->save($scan);
    $user->save();
});
