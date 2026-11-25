<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Get all categories
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
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories,
        ]);
    }

    // Create a new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where('track', $request->input('track'))),
            ],
            'name_ar' => 'required|string|max:255',
            'track' => 'required|in:tech,business',
            'sort_order' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where(
                        'track',
                        $request->input('track', $category->track)
                    ))
                    ->ignore($category->id),
            ],
            'name_ar' => 'sometimes|required|string|max:255',
            'track' => 'sometimes|required|in:tech,business',
            'sort_order' => 'sometimes|required|integer|min:0',
            'description' => 'sometimes|nullable|string',
            'image' => 'sometimes|nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category->fresh(),
        ]);
    }

    // Delete an existing category
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
