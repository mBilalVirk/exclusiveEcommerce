<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    /**
     * Get all products with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', '');
        $category = $request->input('category', '');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        $products = $query->orderBy($sortBy, $sortOrder)
                         ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $products,
        ]);
    }

    /**
     * Create a new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|url',
            'is_new' => 'nullable|boolean',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Get single product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $product,
        ]);
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:products,name,' . $id,
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category' => 'sometimes|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'image' => 'nullable|url',
            'is_available' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
        ]);

        $product->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    /**
     * Bulk update stock
     */
    public function bulkUpdateStock(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:products',
            'products.*.stock' => 'required|integer|min:0',
        ]);

        foreach ($validated['products'] as $item) {
            Product::find($item['id'])->update(['stock' => $item['stock']]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Stock updated successfully',
        ]);
    }
}