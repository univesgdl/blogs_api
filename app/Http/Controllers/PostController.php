<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Post;
use App\Models\Tag;
// use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
        return response()->json($post->load(['meta', 'blocks']), 200);
    }

    public function tags(Post $post){
        return response()->json($post->tags, 200);
    }

    public function categories(Post $post){
        return response()->json($post->categories, 200);
    }

    public function blocks(Post $post){
        $blocks = $post->blocks()->get();
        return response()->json($blocks, 200);
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
            'extract' => 'required',
            // 'published_at' => 'required',
            'keywords' => 'required',
            'description' => 'required',
        ]);

        $post->title = $request->title;
        $post->slug = Str::slug($request->slug);
        $post->extract = $request->extract;
        // $post->published_at = $request->published_at;
        $post->meta->keywords = $request->keywords; 
        $post->meta->description = $request->description; 
        
        $post->save();

        return response()->json($post, 200);
    }

    public function updateImage(Post $post, Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $imageName = $post->featured_image;
        $prevImage = $post->featured_image;

        if ($request->file('image')) {
            // guardar imagen
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            Storage::disk('public')->putFileAs('images', $image, $imageName);

        }

        $post->featured_image = $imageName;
        $post->save();

        if ($post->featured_image != $prevImage) {
            // borrar imagen anterior
            if (Storage::disk('public')->exists('images/' . $prevImage)) {
                Storage::disk('public')->delete('images/' . $prevImage);
            }
        }

        return response()->json(["image" => $post->featured_image], 200);
    }

    public function updateSlug(Post $post, Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:posts,slug,' . $post->id . '|max:255',
        ]);

        $post->slug = $request->slug;
        $post->save();

        return response()->json($post, 200);
    }

    public function updateTitle(Post $post, Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $post->title = $request->title;
        $post->save();

        return response()->json(["title" => $post->title], 200);
    }

    public function getCategories(Post $post)
    {
        $categories = Category::select([
            'categories.id', 'categories.name',
            DB::raw("IFNULL((SELECT 1 FROM category_post WHERE post_id=$post->id AND category_id = categories.id LIMIT 1), 0) AS has_post")
        ])
        ->get();
        return response()->json($categories, 200);
    }
    
    public function getTags(Post $post)
    {
        $tags = Tag::select([
            'tags.id', 'tags.name',
            DB::raw("IFNULL((SELECT 1 FROM post_tag WHERE post_id=$post->id AND tag_id = tags.id LIMIT 1), 0) AS has_post")
        ])
        ->get();
        return response()->json($tags, 200);
    }
    
    public function getDomains(Post $post)
    {
        $domains = Domain::select([
            'domains.id', 'domains.name',
            DB::raw("IFNULL((SELECT 1 FROM domain_post WHERE post_id=$post->id AND domain_id = domains.id LIMIT 1), 0) AS has_post")
        ])
        ->get();
        return response()->json($domains, 200);
    }

    public function updateMeta( Post $post, Request $request )
    {
        $request->validate([
            'keywords' => 'required',
            'description' => 'required',
        ]);

        $post->meta->keywords = $request->keywords; 
        $post->meta->description = $request->description; 
        $post->meta->save();

        return response()->json(["meta" => $post->meta], 200);
    }

    public function updateCategory(Post $post, Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'action' => 'required',
            Rule::in(['ATTACH', 'DETACH'])
        ]);

        if($request->action === "ATTACH")
        {
            $post->categories()->attach($request->category_id);
        }else{
            $post->categories()->detach($request->category_id);
        }
        
        return response()->json($post->categories, 200);
    }

    public function updateTag(Post $post, Request $request)
    {
        $request->validate([
            'tag_id' => 'required',
            'action' => 'required',
            Rule::in(['ATTACH', 'DETACH'])
        ]);

        if($request->action === "ATTACH")
        {
            $post->tags()->attach($request->tag_id);
        }else{
            $post->tags()->detach($request->tag_id);
        }
        
        return response()->json($post->tags, 200);
    }

    public function updateDomain( Post $post, Request $request)
    {
        $request->validate([
            'domain_id' => 'required',
            'action' => 'required',
            Rule::in(['ATTACH', 'DETACH'])
        ]);

        if($request->action === "ATTACH")
        {
            $post->domains()->attach($request->domain_id);
        }else{
            $post->domains()->detach($request->domain_id);
        }
        
        return response()->json($post->domains, 200);
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
