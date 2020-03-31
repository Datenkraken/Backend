<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRetentionPolicy;
use App\Models\RetentionPolicy;
use Carbon\Carbon;

/**
 * This Controller is responsible for handling update,
 * create events for the retention policy and displaying the retention policy views.
 */
class RetentionPolicyController extends Controller
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

    /*
     * Display the settings for the retention policy.
     *
     * @return \Illuminate\View\View The retention policy view.
     */
    public function index()
    {
        return view('retention');
    }

    /*
     * Get the retention policy.
     *
     * @return RetentionPolicy
     */
    public function get()
    {
        return RetentionPolicy::first();
    }

    /*
     * Update the retention policy based on the inputs in the request.
     *
     * @param UpdateRetentionPolicy Request object.
     */
    public function update(UpdateRetentionPolicy $request)
    {
        $policy = RetentionPolicy::firstOrNew([]);

        // Reset the executed flag, if the retention date has changed
        $savedGlobalRetentionDate = Carbon::parse($request->input('globalRetentionDate'));
        $isDateDifferent = $savedGlobalRetentionDate->notEqualTo($policy->globalRetentionDate);

        if ($request->input('enableGlobalRetention') === true && $isDateDifferent) {
            $policy->globalRetentionExecuted = false;
        }

        $policy->fill($request->validated());

        $policy->save();
    }
}
