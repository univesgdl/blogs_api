<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Domain;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return "ok";
        return response()->json(Domain::all(), 200);
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
        ]);

        Domain::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Domain created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return response()->json($domain, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $domain->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Domain updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return response()->json(['message' => 'Domain deleted successfully'], 200);
    }


    public function getCategories( Domain $domain)
    {

        $categories = Category::select([
            'categories.id', 'categories.name',
            DB::raw("IFNULL((SELECT 1 FROM category_domain WHERE domain_id=$domain->id AND category_id = categories.id LIMIT 1), 0) AS has_domain")
        ])
        ->get();
        return response()->json($categories, 200);

    }

    public function getTags(Domain $domain)
    {
        $tags = Tag::select([
            'tags.id', 'tags.name',
            DB::raw("IFNULL((SELECT 1 FROM domain_tag WHERE domain_id=$domain->id AND tag_id = tags.id LIMIT 1), 0) AS has_domain")
        ])
        ->get();
        return response()->json($tags, 200);
    }

    public function updateCategory(Domain $domain, Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'action' => 'required',
            Rule::in(['ATTACH', 'DETACH'])
        ]);

        if ($request->action === "ATTACH") {
            $domain->categories()->attach($request->category_id);
        } else {
            $domain->categories()->detach($request->category_id);
        }

        return response()->json($domain->categories, 200);
    }

    public function updateTag(Domain $domain, Request $request)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id',
            'action' => 'required',
            Rule::in(['ATTACH', 'DETACH'])
        ]);

        if ($request->action === "ATTACH") {
            $domain->tags()->attach($request->tag_id);
        } else {
            $domain->tags()->detach($request->tag_id);
        }

        return response()->json($domain->tags, 200);
    }
}
