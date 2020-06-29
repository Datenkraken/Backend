<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

/**
 * This Controller is responsible for displaying the nodes tab.
 */
class BluetoothMapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('bluetooth-map');
    }
}
