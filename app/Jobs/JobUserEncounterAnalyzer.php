<?php

namespace App\Jobs;

use App\Models\Datamining\BluetoothDeviceScan;
use App\Models\Datamining\WifiData;
use App\Models\JobLog;
use App\Models\ProcessedData\UserEncounter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class JobUserEncounterAnalyzer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAX_TIME_DIFF = 60 * 5; //5 minutes in UNIX Time
    const JOB_ID = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *        'timestamp',
    'user_id1',
    'user_id2',
    'time_diff'
     * @return void
     */
    public function handle()
    {
        /*$start_time = 0;
        $lastJob = JobLog::where('job_id', self::JOB_ID)->orderBy('timestamp', 'desc')->take(1)->get();
        if (count($lastJob) != 0) {
            $start_time = $lastJob[0]->timestamp->getTimestamp() - self::MAX_TIME_DIFF;
        }*/

        UserEncounter::truncate();

        $deviceScans = BluetoothDeviceScan::raw(function($collection) {
            return $collection->aggregate([
                /*[
                    '$match' => [
                        'timestamp' => [
                            '$lt' => Carbon::now()
                        ]
                    ]
                ],*/
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
        $userEncounters = [];
        foreach($deviceScans as $device) {
            $max = count($device['user_ids']);
            if ($max <= 1) continue;

            $max = $max - 1;
            for ($i = 0; $i < $max; $i++) {
                $max_time = $device['user_ids'][$i]['timestamp']->toDateTime()->getTimestamp() + self::MAX_TIME_DIFF;
                for ($j = ($i + 1); $j < $max && $device['user_ids'][$j]['timestamp']->toDateTime()->getTimestamp() < $max_time; $j++) {
                    if (strcmp($device['user_ids'][$i]['user_id'],$device['user_ids'][$j]['user_id']) != 0) {
                        $userEncounters[] = ['timestamp' => $device['user_ids'][$i]['timestamp'],
                                            'user_id1' => $device['user_ids'][$i]['user_id'],
                                            'user_id2' => $device['user_ids'][$j]['user_id'],
                                            'time_diff' => ($device['user_ids'][$j]['timestamp']->toDateTime()->getTimestamp()
                                                - $device['user_ids'][$i]['timestamp']->toDateTime()->getTimestamp())];
                    }
                }
            }
        }

        var_dump($userEncounters);

        UserEncounter::insert($userEncounters);

        //JobLog::create(['job_id' => self::JOB_ID, 'timestamp' => time()]);
    }
}
