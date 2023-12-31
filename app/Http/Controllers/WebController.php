<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

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
            'post' => $post->load(['comments', 'blocks', 'categories', 'tags', 'meta'])
        ]);
    }

    public function postsrelative(Domain $domain, Post $post)
    {
        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->where('id', '!=', $post->id)->limit(4)->get();

        return response()->json($posts, 200);
    }

    public function categorias_posts(Domain $domain, Category $category)
    {
        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->whereHas('categories', function ($query) use ($category) {
            $query->where('category_id', $category->id);
        })->get();
        // return response()->json($category, 200);
        return response()->json($posts ?? [], 200);
    }

    public function domain_posts_limit( Domain $domain, Request $request )
    {
        $limit = $request->limit ?? 10;
        // return $limit;

        // $posts = Post::with(['blocks' => function ($query) {
        //     $query->where('type', 'texto')->take(2);
        // }])->get();
        

        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->limit($limit)->get();
        
        return response()->json($posts, 200);
    }

    public function domain_posts_paginate( Domain $domain)
    {
        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->paginate(10);
        
        return response()->json($posts, 200);
    }

    public function domain_tag_posts_paginate( Domain $domain, Tag $tag)
    {
        $posts = Post::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->whereHas('tags', function ($query) use ($tag) {
            $query->where('tag_id', $tag->id);
        })->paginate(10);
        
        return response()->json($posts, 200);
    }

    public function domain_category_posts_paginate( Domain $domain, Tag $tag )
    {

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

    public function domain_categories(Domain $domain)
    {
        $categories = Category::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->get();

        return response()->json($categories, 200);
    }

    public function domain_tags(Domain $domain)
    {
        $tags = Tag::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain_id', $domain->id);
        })->get();

        return response()->json($tags, 200);
    }
}
