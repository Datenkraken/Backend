<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePrivacy;
use App\Models\PrivacyPolicy;

/**
 * This Controller is responsible for handling update,
 * create events for the privacy policy and displaying the privacy policy views.
 */
class PrivacyPolicyController extends Controller
{
    /*
     * Display the editor for the privacy policy.
     *
     * @return \Illuminate\View\View The editor view.
     */
    public function edit()
    {
        return view('document-edit')
            ->with('title', __('documentedit.policy-edit'))
            ->with('routePrefix', 'privacy');
    }

    /*
     * Display the privacy policy.
     *
     * @return \Illuminate\View\View The index view.
     */
    public function index()
    {
        $policy = PrivacyPolicy::firstOrCreate([], ['content' => '']);
        return view('document-view')->with('data', $policy);
    }

    /*
     * Get the content of the privacy policy.
     *
     * @return App\Models\PrivacyPolicy
     */
    public function get()
    {
        return PrivacyPolicy::firstOrCreate([], ['content' => '']);
    }

    /*
     * Update the privacy policy based on the inputs in the request.
     *
     * @param UpdatePrivacy Request object.
     */
    public function update(UpdatePrivacy $request)
    {
        PrivacyPolicy::updateOrCreate([], ['content' => $request->input('content')]);
    }
}
