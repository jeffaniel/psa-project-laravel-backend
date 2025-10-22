<?php

namespace App\Http\Controllers;

use App\Models\{Category, Product};
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function publicIndex()
    {
        return response()->json(Category::where('is_active', true)->get());
    }

    public function getProducts($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category->products()->paginate(20));
    }

    public function index()
    {
        return response()->json(Category::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string']);
        $category = Category::create(array_merge($request->all(), ['slug' => str($data['name'])->slug()]));
        return response()->json($category, 201);
    }

    public function show(Category $id)
    {
        return response()->json($id);
    }

    public function update(Request $request, Category $id)
    {
        $id->update($request->all());
        return response()->json($id);
    }

    public function destroy(Category $id)
    {
        $id->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
