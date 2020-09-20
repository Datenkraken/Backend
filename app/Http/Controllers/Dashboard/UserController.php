<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRole;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * This Controller is responsible for handling update,
 * create and delete events for users.
 */
class UserController extends Controller
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
        $this->middleware(['auth', 'is_admin']);
        $this->user = $user;
    }

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('users')->with('users', $this->user->all());
    }

    /**
     * Delete the given user and redirect to users view.
     */
    public function delete(Request $request)
    {
        $ids = $request->input('ids');
        foreach ($ids as $id) {
            $this->user->delete($id);
        }
    }

    /**
     * Get all users if json is requested.
     * Redirects if json is not requested.
     *
     * @return \Illuminate\Support\Collection
     */
    public function users()
    {
        return $this->user->all();
    }

    public function role(UpdateRole $request)
    {
        $data = $request->validated();

        $instance = $this->user->find($data['id']);
        $instance->is_admin = $data['is_admin'];
        $instance->save();
    }

    public function user($id)
    {
        return view('user')->with('user', $id);
    }
}
