<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use App\Tables\Categories;
use ProtoneMedia\Splade\Facades\Toast;


class CategoryController extends Controller
{
    public function index()
    {

        return view('categories.index', [
            'categories' => Categories::class
        ]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        Category::create($request->validated());

        Toast::title(__('New Category Created Successfuly'))->autoDismiss(2);

        return to_route('categories.index');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryStoreRequest $request, Category $category)
    {
        $category->update($request->validated());

        Toast::title(__('Category Updated Successfuly'))->autoDismiss(2);

        return to_route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        Toast::title(__('Category Deleted Successfuly'))->autoDismiss(2);

        return redirect()->back();
    }
}
