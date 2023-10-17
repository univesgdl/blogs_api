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
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required',
            Rule::in(['TEXT', 'IMAGE', 'VIDEO']),
            'content' => 'required',
            'width' => 'nullable',
            'height' => 'nullable',
            'align' => 'nullable',
            'position' => 'required|integer',
        ]);

        $block = Block::create([
            'type' => $request->type,
            'content' => $request->content,
            'width' => $request->width,
            'height' => $request->height,
            'align' => $request->align,
            'position' => $request->position,
            'post_id' => $post->id,
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
            'width' => 'nullable',
            'height' => 'nullable',
            'align' => 'nullable',
            'position' => 'required|integer'
        ]);

        $block->update([
            'content' => $request->content,
            'width' => $request->width,
            'height' => $request->height,
            'align' => $request->align,
            'position' => $request->position
        ]);

        return response()->json($block, 200);
    }

    public function updateOrder(Request $request, Post $post)
    {
        $request->validate([
            'blocks' => 'required'
        ]);

        foreach ($request->blocks as $block) {
            Block::where('id', $block['id'])->update([
                'position' => $block['position']
            ]);
        }

        return response()->json($post->blocks, 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block)
    {
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
