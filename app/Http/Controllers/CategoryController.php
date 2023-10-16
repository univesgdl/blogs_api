<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Category::all(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $imageName = null;

        if ($request->file('image')) {
            // guardar imagen
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
        }
        $randomStrig = Str::random(5);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $randomStrig,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json($category, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'slug' => 'required|unique:categories,slug,' . $category->id . '|max:255',
        ]);

        $imageName = $category->image;
        $prevImage = $category->image;

        if ($request->file('image')) {
            // guardar imagen
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            // Almacenar la imagen en el disco 'public'
            Storage::disk('public')->putFileAs('images', $image, $imageName);
        }

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        if ($imageName != $prevImage) {
            // borrar imagen anterior
            if (Storage::disk('public')->exists('images/' . $prevImage)) {
                Storage::disk('public')->delete('images/' . $prevImage);
            }
        }

        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(!$category){
            return response()->json(['message' => "Category not found"], 404);
        }

        if ($category->image) {
            // borrar imagen
            if (Storage::disk('public')->exists('images/' . $category->image)) {
                Storage::disk('public')->delete('images/' . $category->image);
            }
        }

        $category->delete();

        return response()->json(['message' => "Category deleted successfully"], 204);
    }
}
