<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateImprint;
use App\Models\Imprint;

/**
 * This Controller is responsible for handling update,
 * create events for the imprint and displaying the imprint views.
 */
class ImprintController extends Controller
{
    /*
     * Display the editor for the imprint.
     *
     * @return \Illuminate\View\View The editor view.
     */
    public function edit()
    {
        return view('document-edit')
            ->with('title', __('documentedit.imprint-edit'))
            ->with('routePrefix', 'imprint');
    }

    /*
     * Display the imprint.
     *
     * @return \Illuminate\View\View The index view.
     */
    public function index()
    {
        $imprint = Imprint::firstOrCreate([], ['content' => '']);
        return view('document-view')->with('data', $imprint);
    }

    /*
     * Get the content of the imprint.
     *
     * @return App\Models\Imprint
     */
    public function get()
    {
        return Imprint::firstOrCreate([], ['content' => '']);
    }

    /*
     * Update the imprint based on the inputs in the request.
     *
     * @param UpdateImprint Request object.
     */
    public function update(UpdateImprint $request)
    {
        Imprint::updateOrCreate([], ['content' => $request->content]);
    }
}
