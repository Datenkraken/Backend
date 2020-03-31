<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSource;
use App\Http\Requests\UpdateSource;
use App\Models\RssSource;

/**
 * This Controller is responsible for handling update,
 * create and delete events for sources.
 */
class SourceController extends Controller
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
     * Show the user dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('sources');
    }

    /**
     * Get all sources.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function sources()
    {
        return RssSource::with('categories')->get();
    }

    /**
     * Adds a source.
     *
     * @param AddSource $request Injected request.
     *
     * @return void
     */
    public function addSource(AddSource $request)
    {
        $source = new RssSource();

        $source->name = $request->input('name');
        $source->url = $request->input('url');

        $source->save();

        if (! $request->input('categories')) {
            // remove all categories
            $source->categories()->sync([]);
        } else {
            foreach ($request->input('categories') as $category) {
                $source->categories()->attach($category);
            }
        }
    }

    /**
     * Delete the given source
     *
     * @param int $id This is the id of the source to be deleted.
     *
     * @return void
     */
    public function delete($id)
    {
        RssSource::findOrFail($id)->delete();
    }

    /**
     * Updates a source.
     *
     * @param UpdateSource $request Injected request.
     *
     * @return void
     */
    public function update(UpdateSource $request)
    {
        $source = RssSource::findOrFail($request->input('id'));
        $source->name = $request->input('name');
        $source->url = $request->input('url');

        if (! $request->input('categories')) {
            // remove all categories
            $source->categories()->sync([]);
        } else {
            foreach ($request->input('categories') as $category) {
                $source->categories()->attach($category);
            }
        }

        $source->save();
    }
}
