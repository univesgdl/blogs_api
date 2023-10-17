<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function postsindex()
    {
        $posts = Post::withCount('comments')->get();

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

    public function categoryposts(Category $category)
    {
        return response()->json([
            'posts' => $category->posts
        ]);

    }

    public function tagposts(Tag $tag)
    {
        return response()->json([
            'posts' => $tag->posts
        ]);
    }

    public function comments( Post $post)
    {
        return response()->json([
            'comments' => $post->comments
        ]);
    }

    public function postComments( Post $post)
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
