<?php

namespace App\Http\Controllers\Forums;

use App\Forums\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Create a new TagController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tag::all();
        // return Tag::with([
        //     // 'subcategories',
        //     // 'releases' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        //   ])all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'colour' => 'required',
            'order' => 'required|integer'
        ]);

        //Create the Tag
        $tag = Tag::create([
            'name' => $request->name,
            'colour' => $request->colour,
            'order' => $request->order
        ]);

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Forums\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Forums\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'colour' => 'required',
            'order' => 'required|integer'
        ]);
        $tag = Tag::find($id);
            $tag->update($request->all());
        $tag->save();

        return response()->json($tag, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Forums\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag = Tag::find($id);
        $tag->delete();

        return response()->json(null, 204);
    }
}
