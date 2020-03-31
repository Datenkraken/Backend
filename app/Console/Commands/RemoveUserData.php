<?php

namespace App\Console\Commands;

use App\Models\RetentionPolicy;
use App\Services\Retention\DeleteAllUserdataService;
use App\Services\Retention\DeleteExpiredUserdataService;
use Illuminate\Console\Command;

class RemoveUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userdata:remove
                            {--expired : Check the individual expiry dates on all users and remove users that exceeded the time limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will remove all user data that the system knows of.';

    /**
     * The Service responsible for the user deletion after the global time limit.
     *
     * @var DeleteAllUserdataService
     */
    protected $deletionService;

    /**
     * The Service responsible for the user deletion based on their individual creation time.
     *
     * @var DeleteExpiredUserdataService
     */
    protected $expiredDeletionService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DeleteAllUserdataService $deletionService, DeleteExpiredUserdataService $expiredDeletionService)
    {
        parent::__construct();
        $this->deletionService = $deletionService;
        $this->expiredDeletionService = $expiredDeletionService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $policy = RetentionPolicy::first();

        if ($policy === null) {
            $this->error('No retention policy defined. Please create a policy in the admin dashboard!');
        }

        if ($this->option('expired')) {
            $this->line('Checking for expired user accounts...');
            $result = $this->expiredDeletionService->handle($policy->defaultRetentionDays);
        } else {
            $this->line('Checking for the global retention limit...');
            $result = $this->deletionService->handle();
        }

        $this->line('Removed: ' . $result['removedUsersCount'] . ' / ' . $result['removableUsersCount'] . ' users.');

        if ($result['success']) {
            $this->info('Removed user data successfully!');
        } else {
            $this->error('Could not remove some user data. Please retry and see the error log for further information!');
        }
    }
}
