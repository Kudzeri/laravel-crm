<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with(['images', 'categories', 'tags', 'attributes'])->get();
    }

    public function show(Product $product)
    {
        return $product->load(['images', 'categories', 'tags', 'attributes']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|unique:products',
            'sku' => 'nullable|string',
            'type' => 'required|string',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'on_sale' => 'boolean',
            'price' => 'nullable|numeric',
            'regular_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'currency_code' => 'string',
            'stock_quantity' => 'nullable|integer',
            'is_in_stock' => 'boolean',
            'permalink' => 'nullable|url',
        ]);

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'slug' => 'sometimes|string|unique:products,slug,' . $product->id,
            'sku' => 'nullable|string',
            'type' => 'sometimes|string',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'on_sale' => 'boolean',
            'price' => 'nullable|numeric',
            'regular_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'currency_code' => 'string',
            'stock_quantity' => 'nullable|integer',
            'is_in_stock' => 'boolean',
            'permalink' => 'nullable|url',
        ]);

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
