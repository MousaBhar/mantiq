<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display all categories.
     */
    public function index(Request $request)
    {
        $request->validate([
            'track' => 'nullable|in:tech,business',
        ]);

        $query = Category::query();

        if ($request->filled('track')) {
            $query->where('track', $request->input('track'));
        }

        $categories = $query
            ->orderBy('track')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where('track', $request->input('track')),
            ],
            'name_ar' => 'required|string|max:255',
            'track' => 'required|in:tech,business',
            'sort_order' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where('track', $request->input('track'))
                    ->ignore($category->id),
            ],
            'name_ar' => 'required|string|max:255',
            'track' => 'required|in:tech,business',
            'sort_order' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
