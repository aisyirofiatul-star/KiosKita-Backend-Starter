<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Daftar produk berhasil diambil',
            'data' => ProductResource::collection($products)->resolve($request),
        ]);
    }

    public function show(Request $request, Product $product)
    {
        return response()->json([
            'message' => 'Detail produk berhasil diambil',
            'data' => (new ProductResource($product))->resolve($request),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data' => (new ProductResource($product))->resolve($request),
        ], 201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($validated);
        $product->refresh();

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data' => (new ProductResource($product))->resolve($request),
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
            'data' => null,
        ]);
    }
}
