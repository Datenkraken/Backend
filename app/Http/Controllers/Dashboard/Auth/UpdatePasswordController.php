<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePassword;
use App\Services\Account\UserUpdatePasswordService;

/**
 * This controller is responsible for handling password update requests.
 */
class UpdatePasswordController extends Controller
{
    /**
     * @var UserUpdatePasswordService
     */
    protected $updatePasswordService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserUpdatePasswordService $updatePasswordService)
    {
        $this->middleware('auth');
        $this->updatePasswordService = $updatePasswordService;
    }

    /**
     * Show the password update page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('account.password');
    }

    /**
     * Handle the request to update the password.
     *
     * @param UpdatePassword $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdatePassword $request)
    {
        $params = $request->validated();
        $user = $request->user();

        $this->updatePasswordService->handle($user, $params);

        return back()->with('status', __('passwords.reset'));
    }
}
