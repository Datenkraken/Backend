<?php

namespace App\Services\Account;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Service for updating the user password
 */
class UserUpdatePasswordService
{
    /**
     * Update the users password.
     *
     * @param User    $user
     * @param array   $params
     *
     * @return User
     */
    public function handle(User $user, array $params): User
    {
        $user->password = Hash::make($params['password']);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        Auth::guard()->logout($user);

        return $user;
    }
}
