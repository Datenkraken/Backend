<?php

namespace App\Jobs;

use App\Models\Datamining\BluetoothDeviceScan;
use App\Models\ProcessedData\UserEncounter;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MongoDB\BSON\UTCDateTime;

class JobUserEncounterAnalyzer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAX_TIME_DIFF = 30 * 5; //2.5 minutes in UNIX Time
    const ANALYZE_INTERVAL = 24 * 60 * 60; //1 Day

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function handle()
    {
        $deviceScans = BluetoothDeviceScan::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'timestamp' => [
                            '$gte' => new UTCDateTime((time() - self::ANALYZE_INTERVAL) * 1000)
                        ]
                    ]
                ],
                [
                    '$sort' => [
                        'timestamp' => 1
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$address',
                        'user_ids' => [
                            '$push' => [
                                'user_id' => '$user_id',
                                'timestamp' => '$timestamp'
                            ]
                        ]
                    ]
                ]
            ]);
        });

        if ($deviceScans == null || count($deviceScans) == 0) {
            return;
        }

        $dbUsers = User::all();
        $users = [];
        foreach ($dbUsers as $user) {
            $users[$user->_id] = $user;
        }

        $userEncounters = [];
        foreach($deviceScans as $device) {
            $scans = $device['user_ids'];
            $max = count($scans) - 1;
            if ($max <= 0) continue;
            for ($i = 0; $i < $max; $i++) {
                $max_time = $scans[$i]['timestamp']->toDateTime()->getTimestamp() + self::MAX_TIME_DIFF;
                $encounters = [];
                for ($j = ($i + 1); $j <= $max && $scans[$j]['timestamp']->toDateTime()->getTimestamp() <= $max_time; $j++) {
                    $identifier = $this->getIdentifier($scans[$i]['user_id'], $scans[$j]['user_id']);
                    if ($identifier != null) {
                        if (!in_array($identifier, $encounters)) {
                            $sortedUsersId = [$scans[$i]['user_id'], $scans[$j]['user_id']];
                            sort($sortedUsersId);

                            $encounters[$identifier] = [
                                'enc' =>   $this->getUserEncounter(
                                                $scans[$i]['timestamp']->toDateTime()->getTimestamp(),
                                                $scans[$j]['timestamp']->toDateTime()->getTimestamp()
                                            ),
                                'participants' => [
                                    $users[$sortedUsersId[0]],
                                    $users[$sortedUsersId[1]]
                                ]
                            ];
                        } else {
                            $encounters[$identifier]['enc']->end_time = new UTCDateTime($scans[$j]['timestamp'] * 1000);
                        }
                    }
                }
                $userEncounters = array_merge($userEncounters, array_values($encounters));
            }
        }
        foreach ($userEncounters as $encounter) {
            $encounter['enc']->save();
            $encounter['participants'][0]->dataEncounters()->attach($encounter['enc']);
            $encounter['participants'][1]->dataEncounters()->attach($encounter['enc']);
        }
    }

    private function getIdentifier(String $userId1, String $userId2) {
        $cmp = strcmp($userId1, $userId2);
        if ($cmp > 0) {
            return $userId2 . $userId1;
        } elseif ($cmp < 0) {
            return $userId1 . $userId2;
        }
        return null;
    }

    private function getUserEncounter(int $timestamp1, int $timestamp2) {
        $encounter = new UserEncounter();
        $encounter->fill(['start_time' => $timestamp1, 'end_time' => $timestamp2]);
        return $encounter;
    }
}
