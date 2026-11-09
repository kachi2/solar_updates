<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\NameRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function index(string $id): View
    {
        return view('manage.subCategory.index', [
            'subcategory' => SubCategory::where('category_id', $this->decryptId($id))->get(),
            'bheading' => 'Sub Category',
            'breadcrumb' => 'Index',
        ]);
    }

    public function create(string $id): View
    {
        return view('manage.subCategory.create', [
            'category' => Category::findOrFail($this->decryptId($id)),
            'bheading' => 'Sub Category',
            'breadcrumb' => 'create',
        ]);
    }

    public function store(NameRequest $request, string $id): RedirectResponse
    {
        SubCategory::create([
            'name' => $request->input('name'),
            'category_id' => $this->decryptId($id),
        ]);

        return back()->with('alert', 'success')->with('message', 'Category Added Successfully');
    }

    public function edit(string $id): View
    {
        return view('manage.subCategory.edit', [
            'category' => SubCategory::findOrFail($this->decryptId($id)),
            'bheading' => 'Sub Category',
            'breadcrumb' => 'Edit',
        ]);
    }

    public function update(NameRequest $request, string $id): RedirectResponse
    {
        SubCategory::findOrFail($this->decryptId($id))->update(['name' => $request->input('name')]);

        return back()->with('alert', 'success')->with('message', 'Sub Category Updated Successfully');
    }
}
