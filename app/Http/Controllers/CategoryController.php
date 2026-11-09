<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private readonly ImageUploadService $uploads) {}

    public function index(): View
    {
        return view('manage.category.index', [
            'category' => Category::latest()->get(),
            'bheading' => 'Category',
            'breadcrumb' => 'Index',
        ]);
    }

    public function create(): View
    {
        return view('manage.category.create', [
            'bheading' => 'Category',
            'breadcrumb' => 'Index',
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->input('name'),
            'image' => $this->uploads->store($request->file('image'), 'category'),
        ]);

        return back()->with('alert', 'success')->with('message', 'Category Added Successfully');
    }

    public function edit(string $category): View
    {
        return view('manage.category.edit', [
            'category' => Category::findOrFail($this->decryptId($category)),
            'bheading' => 'Category',
            'breadcrumb' => 'Edit Catogry',
        ]);
    }

    public function update(CategoryRequest $request, string $category): RedirectResponse
    {
        $category = Category::findOrFail($this->decryptId($category));
        $category->name = $request->input('name');

        if ($request->hasFile('image')) {
            $category->image = $this->uploads->store($request->file('image'), 'category');
        }

        $category->save();

        return back()->with('alert', 'success')->with('message', 'Category Updated Successfully');
    }
}
