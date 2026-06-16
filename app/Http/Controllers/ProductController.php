<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = ProductModel::all();
        return response()->json([
            'Status' => 'Success',
            'Message' => 'Products retrieved successfully',
            'Data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        if($validatedData['price'] < 0 || $validatedData['quantity'] < 0) {
            return response()->json([
                'Status' => 'Error',
                'Message' => 'Price and quantity must be non-negative'
            ], 422);
        }

        if(ProductModel::where('name', $validatedData['name'])->exists()) {
            return response()->json([
                'Status' => 'Error',
                'Message' => 'Product with this name already exists'
            ], 409);
        }

        $product = ProductModel::create($validatedData);

        return response()->json([
            'Status' => 'Success',
            'Message' => 'Product created successfully',
            'Data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = ProductModel::findOrFail($id);
        return response()->json([
            'Status' => 'Success',
            'Message' => 'Product retrieved successfully',
            'Data' => $product
        ]   );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = ProductModel::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($request->hasFile('image')) {
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        if($validatedData['price'] < 0 || $validatedData['quantity'] < 0) {
            return response()->json([
                'Status' => 'Error',
                'Message' => 'Price and quantity must be non-negative'
            ], 422);
        }

        $product->update($validatedData);

        return response()->json([
            'Status' => 'Success',
            'Message' => 'Product updated successfully',
            'Data' => $product
        ]   );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = ProductModel::findOrFail($id);
        $product->delete();
        return response()->json([
            'Status' => 'Success',
            'Message' => 'Product deleted successfully'
        ], 200);
    }
}
