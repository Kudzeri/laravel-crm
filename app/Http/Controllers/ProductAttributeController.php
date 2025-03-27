<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string',
            'value' => 'required|string',
        ]);

        return ProductAttribute::create($data);
    }

    public function update(Request $request, ProductAttribute $attribute)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'value' => 'sometimes|string',
        ]);

        $attribute->update($data);
        return $attribute;
    }

    public function destroy(ProductAttribute $attribute)
    {
        $attribute->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

