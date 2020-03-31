<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategory;
use App\Http\Requests\UpdateCategory;
use App\Models\Category;

/**
 * This Controller is responsible for handling update,
 * create and delete events for categories.
 */
class CategoryController extends Controller
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
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function categories()
    {
        return Category::all();
    }

    /**
     * Adds a category.
     *
     * @param AddCategory $request Injected request.
     *
     * @return void
     */
    public function addCategory(AddCategory $request)
    {
        $category = new Category();

        $category->name = $request->input('name');

        $category->save();
    }

    /**
     * Delete the given category
     *
     * @param int $id This is the id of the category to be deleted.
     *
     * @return void
     */
    public function delete($id)
    {
        Category::findOrFail($id)->delete();
    }

    /**
     * Updates a category.
     *
     * @param UpdateCategory $request Injected request.
     *
     * @return void
     */
    public function update(UpdateCategory $request)
    {
        $category = Category::findOrFail($request->input('id'));
        $category->name = $request->input('name');

        $category->save();
    }
}
