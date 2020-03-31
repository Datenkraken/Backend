<?php

namespace App\Services\Retention;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

/**
 * Service for deleting all expired users.
 */
class DeleteExpiredUserdataService
{
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new instance.
     *
     * @param  UserRepository  $userRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Removes all non-admin user accounts and their data.
     *
     * @param int $days the amount of days after which a user should be removed.
     *
     * @return array
     */
    public function handle(int $days): array
    {
        $normalUsers = $this->userRepository->getBuilder()->where('is_admin', '!=', true)->get();
        $success = true;
        $userCount = 0;
        $userDeletions = 0;

        foreach ($normalUsers as $user) {
            $expiryDate = $user->created_at->addDays($days);

            if ($expiryDate->isFuture()) {
                continue;
            }

            $userCount++;

            $result = $this->userRepository->delete($user->id);

            if ($result === true) {
                $userDeletions++;
            } else {
                Log::info('DeleteExpiredUserdata: Could not delete user with id: ' . $user->id);
            }

            $success = $success && $result;
        }

        return [
            'success' => $success,
            'removableUsersCount' => $userCount,
            'removedUsersCount' => $userDeletions,
        ];
    }
}
