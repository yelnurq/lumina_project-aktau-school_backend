<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }
    public function admin()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json($category, 201);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->with('news')->firstOrFail();
        return response()->json($category);
    }

    public function update(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $request->validate(['name' => 'required|string|max:255']);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json($category);
    }

    public function destroy($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->delete();

        return response()->json(['message' => 'Категория удалена']);
    }
}
