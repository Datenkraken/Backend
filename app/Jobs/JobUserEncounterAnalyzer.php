<?php

namespace App\Jobs;

use App\Models\Datamining\BluetoothDeviceScan;
use App\Models\ProcessedData\UserEncounter;
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
                            '$gte' => new UTCDateTime((time() - 24 * 60 * 60) * 1000)
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

        $userEncounters = [];
        foreach($deviceScans as $device) {
            $scans = $device['user_ids'];
            $max = count($scans) - 1;
            if ($max <= 0) continue;
            for ($i = 0; $i < $max; $i++) {
                $max_time = $scans[$i]['timestamp']->toDateTime()->getTimestamp() + self::MAX_TIME_DIFF;
                $encounters = [];
                for ($j = ($i + 1); $j < $max && $scans[$j]['timestamp']->toDateTime()->getTimestamp() < $max_time; $j++) {
                    $identifier = $this->getIdentifier($scans[$i]['user_id'], $scans[$j]['user_id']);
                    if ($identifier != null) {
                        if (!in_array($identifier, $encounters)) {
                            $encounters[$identifier] = $this->getUserEncounter(
                                $scans[$i]['user_id'],
                                $scans[$i]['timestamp']->toDateTime()->getTimestamp(),
                                $scans[$j]['user_id'],
                                $scans[$j]['timestamp']->toDateTime()->getTimestamp()
                            );
                        } else {
                            $encounters[$identifier]['end_time'] = new UTCDateTime($scans[$j]['timestamp'] * 1000);
                        }
                    }
                }
                $userEncounters = array_merge($userEncounters, array_values($encounters));
            }
        }

        if (count($userEncounters) > 0) {
            UserEncounter::insert(array_values($userEncounters));
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

    private function getUserEncounter(String $userId1, int $timestamp1, String $userId2, int $timestamp2) {
        $users = [$userId1, $userId2];
        sort($users);
        $timestamps = [$timestamp1, $timestamp2]; //timestamp 1 will always be smaller than timestamp 2
        return ['user_id1' => $users[0],
            'user_id2' => $users[1],
            'start_time' => new UTCDateTime($timestamps[0] * 1000),
            'end_time' => new UTCDateTime($timestamps[1] * 1000)];
    }
}
