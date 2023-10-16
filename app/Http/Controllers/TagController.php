<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Tag::all(), 200);
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
        ]);

        $randomStrig = Str::random(5);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $randomStrig,
            'description' => $request->description,
        ]);

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return response()->json($tag, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'slug' => 'required|unique:tags,slug,' . $tag->id . '|max:255',
        ]);

        $randomStrig = Str::random(5);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $randomStrig,
            'description' => $request->description,
        ]);

        return response()->json($tag, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(["msg" => "Tag deleted"], 204);

    }
}
