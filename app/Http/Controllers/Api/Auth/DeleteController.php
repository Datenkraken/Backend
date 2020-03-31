<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * This controller is responsible for handling the account removal.
 */
class DeleteController extends Controller
{
    /*
     * @var UserRepository
     */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Removes the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $user = $request->user();

        $userDeletedSuccess = false;

        try {
            $userDeletedSuccess = $this->user->delete($user->_id);
        } catch (\Exception $exception) {
            response()->json(['error' => $exception->getMessage()], 403);
        }

        if ($userDeletedSuccess === false) {
            return response('Could not delete account, please try again!', 405);
        }

        return $this->returnNoContent();
    }
}
