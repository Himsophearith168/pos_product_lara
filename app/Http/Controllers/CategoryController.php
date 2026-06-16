<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'Status' => 'Success',
            'Data' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name'])
        ]);

        return response()->json([
            'Status' => 'Success',
            'Message' => 'Category created successfully',
            'Data' => $category
        ], 201);
    }
}
