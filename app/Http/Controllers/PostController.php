<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Post::all(), 200);
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
            'title' => 'required'
        ]);

        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
        ]);

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json($post->load(['meta', 'comments']), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,' . $post->id . '|max:255',
            'extract' => 'required',
            'featured_image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'published_at' => 'required',
        ]);

        $imageName = $post->featured_image;
        $prevImage = $post->featured_image;

        if ($request->file('featured_image')) {
            // guardar imagen
            $image = $request->file('featured_image');
            $imageName = time() . '.' . $image->extension();
            // Almacenar la imagen en el disco 'public'
            Storage::disk('public')->putFileAs('images', $image, $imageName);
        }

        $post->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'extract' => $request->extract,
            'featured_image' => $imageName,
            'published_at' => $request->published_at,
        ]);

        if ($imageName != $prevImage) {
            // borrar imagen anterior
            if (Storage::disk('public')->exists('images/' . $prevImage)) {
                Storage::disk('public')->delete('images/' . $prevImage);
            }
        }

        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            // borrar imagen anterior
            if (Storage::disk('public')->exists('images/' . $post->featured_image)) {
                Storage::disk('public')->delete('images/' . $post->featured_image);
            }
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
