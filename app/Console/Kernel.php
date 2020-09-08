<?php

namespace App\Console;

use App\Console\Commands\AddUser;
use App\Console\Commands\RemoveUserData;
use App\Jobs\JobUserEncounterAnalyzer;
use App\Models\RetentionPolicy;
use App\Repositories\RetentionPolicyRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RemoveUserData::class,
        AddUser::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $retentionPolicyRepository = resolve(RetentionPolicyRepository::class);

        $schedule->command('userdata:remove')
            ->everyMinute()
            ->withoutOverlapping(10)
            ->when($retentionPolicyRepository->canGlobalRetentionBeScheduled())
            ->after(function () {
                $policy = RetentionPolicy::first();

                if ($policy !== null) {
                    $policy->globalRetentionExecuted = true;
                    $policy->save();
                }
            });

        $schedule->command('userdata:remove --expired')
            ->everyMinute()
            ->when($retentionPolicyRepository->canDefaultRetentionBeScheduled());

        $schedule->job(new JobUserEncounterAnalyzer())->dailyAt('07:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
