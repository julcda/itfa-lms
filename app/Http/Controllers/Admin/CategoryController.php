<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['courses', 'books'])->paginate(15);
        $parents = Category::where('is_active', true)->get();
        return view('admin.categories.index', compact('categories', 'parents'));
    }

    public function create()
    {
        $parents = Category::where('is_active', true)->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'type'           => 'required|in:course,book,both',
            'parent_id'      => 'nullable|exists:categories,id',
            'description'    => 'nullable|string',
            'description_ar' => 'nullable|string',
            'icon'           => 'nullable|string|max:50',
            'order'          => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function show(Category $category)
    {
        $category->load('children', 'courses', 'books');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->where('is_active', true)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'type'           => 'required|in:course,book,both',
            'parent_id'      => 'nullable|exists:categories,id',
            'description'    => 'nullable|string',
            'icon'           => 'nullable|string|max:50',
            'order'          => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
