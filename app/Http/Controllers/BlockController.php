<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class BlockController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            Rule::in(['text', 'quote', 'heading', 'html', 'image', 'video']),
            'content' => 'nullable',
            'post_id' => 'required|exists:posts,id',
        ]);

        $block = Block::create([
            'type' => $request->type,
            'content' => $request->content,
            'post_id' => $request->post_id,
        ]);

        return response()->json($block, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Block $block)
    {
        $request->validate([
            'content' => 'required',
        ]);

        if($block->type === "image"){
            $request->validate([
                'content' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $image = $request->file('content');
            $imageName = time() . '.' . $image->extension();
            Storage::disk('public')->putFileAs('images', $image, $imageName);

            $block->update([
                'content' => $imageName,
            ]);

            return response()->json($block, 200);
        }

        $block->update([
            'content' => $request->content,
        ]);

        return response()->json($block, 200);
    }

    public function updateExtra(Block $block, Request $request)
    {
        $request->validate([
            'extra' => 'required'
        ]);

        $block->update([
            'extra'=> $request->extra
        ]);

        return response()->json($block, 200);
    }

    public function updateImage(Block $block, Request $request)
    {
        $request->validate([
            'content' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $image = $request->file('content');
        $imageName = time() . '.' . $image->extension();
        Storage::disk('public')->putFileAs('images', $image, $imageName);

        $block->update([
            'content' => $imageName,
        ]);

        return response()->json($block, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block)
    {
        if($block->type === "image"){
            if(Storage::disk('public')->exists('images/' . $block->content)){
                Storage::disk('public')->delete('images/' . $block->content);
            }
        }
        $block->delete();

        return response()->json(["message" => "Block eliminado"], 204);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        Storage::disk('public')->putFileAs('images', $image, $imageName);

        return response()->json(["image" => $imageName], 200);
    }
}
