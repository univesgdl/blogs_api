<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Post;
use App\Models\Tag;

class WebController extends Controller
{
    public function postsindex(Domain $domain)
    {
        // $posts = Post::withCount('comments')->get();
        $posts = $domain->posts()->get();

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function postsingle(Post $post)
    {
        return response()->json([
            'post' => $post,
            'comments' => $post->comments->count()
        ]);
    }

    public function categorias_posts(Domain $domain, Category $category)
    {
        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->whereHas('categories', function ($query) use ($category) {
            $query->where('category_id', $category->id);
        })->get();

        return response()->json($posts, 200);
    }

    public function tagposts(Domain $domain, Tag $tag)
    {
        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->whereHas('tags', function ($query) use ($tag) {
            $query->where('tag_id', $tag->id);
        })->get();

        return response()->json($posts, 200);
    }

    public function comments(Post $post)
    {
        return response()->json([
            'comments' => $post->comments
        ]);
    }

    public function postComments(Post $post)
    {
        return response()->json([
            'comments' => $post->comments
        ]);
    }

    public function saveComment(Post $post)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'comment' => 'required'
        ]);

        $post->comments()->create($data);

        return response()->json([
            'message' => 'Comment added successfully'
        ]);
    }
}
