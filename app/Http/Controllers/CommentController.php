<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email'  => 'required|email',
            'phone' => 'required',
            'comment' => 'required'
        ]);
        
        $comment = $post->comments()->create([
            'name' => $request->name,
            'email'  => $request->email,
            'phone' => $request->phone,
            'comment' => $request->comment
        ]);

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
