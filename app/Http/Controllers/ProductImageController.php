<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url' => 'required|url',
            'thumbnail' => 'nullable|url',
            'alt' => 'nullable|string',
        ]);

        $image = ProductImage::create($data);
        return response()->json($image, 201);
    }

    public function destroy(ProductImage $image)
    {
        $image->delete();
        return response()->json(['message' => 'Image deleted']);
    }
}

