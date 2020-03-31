<?php

namespace App\Services\Retention;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

/**
 * Service for deleting all non admin users.
 */
class DeleteAllUserdataService
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
     * @return array
     */
    public function handle(): array
    {
        $normalUsers = $this->userRepository->getBuilder()->where('is_admin', '!=', true)->get();
        $success = true;
        $userCount = count($normalUsers);
        $userDeletions = 0;

        foreach ($normalUsers as $user) {
            $result = $this->userRepository->delete($user->id);

            if ($result === true) {
                $userDeletions++;
            } else {
                Log::info('DeleteAllUserdata: Could not delete user with id: ' . $user->id);
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
