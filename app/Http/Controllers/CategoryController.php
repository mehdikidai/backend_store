<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        $categories = Cache::remember('categories', now()->addDays(30), function () {
            return Category::all('slug', 'name');
        });
        return response()->json($categories);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|min:2|max:20',
            'slug' => 'required|min:2|max:20|unique:categories,slug',
        ]);

        $category = Category::create($data);

        return response()->json($category, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id, ['id', 'name', 'slug']);
            return response()->json($category);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            "name" => "required|min:2|max:20",
            "slug" => "required|min:2|max:20|unique:categories,slug,{$id}",
        ]);

        $category = Category::findOrFail($id);

        $category->update($data);

        return response()->json($category, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
