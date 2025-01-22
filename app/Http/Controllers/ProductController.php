<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ProductCollection
    {
        $products = Product::with('category', 'colors', 'sizes')->paginate(10);

        return new ProductCollection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {

        $data = $request->validated();

        do {
            $sku = "SKU-" . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());


        $data['sku'] = $sku;

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): ProductResource
    {

        $product = Product::with('category')->findOrFail($id);

        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        Gate::authorize('delete_product');

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
