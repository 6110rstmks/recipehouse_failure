<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {

        // Log::debug(Category::all());
        // Log::debug(Category::latest()->get());
        return Category::latest()->get();
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function maxCategory()
    {
        $maxId = Category::max('id');

        // Log::debug(Category::where('id', '=', $maxId)->get()->toArray());

        // Log::debug(Category::find(Category::max('id')));

        // return Category::where('id', '=', $maxId)->get();
        return Category::find(Category::max('id'));
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return $category;
    }

    public function store(Request $request)
    {
        Log::debug($request);
        $category = new Category();
        $category->title = $request->title;
        $category->save();

        return redirect()
        ->route('category.index');
    }
}
