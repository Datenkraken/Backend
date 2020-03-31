<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\RetentionPolicyRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * This controller handles the registration of new users as well as their
 * validation and creation. By default this controller uses a trait to
 * provide this functionality without requiring any additional code.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * The retention policy repository.
     *
     * @var RetentionPolicyRepository
     */
    protected $retentionPolicyRepository;

    /**
     * Create a new controller instance.
     *
     * @param RetentionPolicyRepository $retentionPolicyRepository
     *
     * @return void
     */
    public function __construct(RetentionPolicyRepository $retentionPolicyRepository)
    {
        $this->middleware('guest')->except('showRegistrationSuccess');
        $this->retentionPolicyRepository = $retentionPolicyRepository;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if ($this->retentionPolicyRepository->isRegistrationBlockedByPolicy()) {
            return redirect(RouteServiceProvider::CLOSED);
        }

        return view('auth.register');
    }

    /**
     * Show the success screen after a registration via the app.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showRegistrationSuccess()
    {
        return view('auth.success');
    }

    /**
     * Show the registration closed notice.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showRegistrationClosed()
    {
        return view('auth.closed');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if ($this->retentionPolicyRepository->isRegistrationBlockedByPolicy()) {
            return redirect(RouteServiceProvider::CLOSED);
        }

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param  mixed  $user
     *
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if ($request->input('origin') === 'app') {
            return redirect(RouteServiceProvider::SUCCESS);
        }

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, User::validationRules());
    }
}
