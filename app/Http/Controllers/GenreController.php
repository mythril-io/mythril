<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Genre;

class GenreController extends Controller
{
    /**
     * Create a new GenreController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Return a paginated set of Genres.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Genre::paginate(10);
    }

    /**
     * Show the form for creating a new Genre.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Genre in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:genres',
            'acronym' => ''
        ]);

        //Create the Genre
        $genre = Genre::create([
            'name' => $request->name,
            'acronym' => $request->acronym
        ]);

        return response()->json($genre, 201);
    }

    /**
     * Display the specified Genre.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Genre.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Genre in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:genres,name,'.$id,
        ]);
        $genre = Genre::find($id);
            $genre->update($request->all());
        $genre->save();

        return response()->json($genre, 200);
    }

    /**
     * Remove the specified Genre from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);
        $genre->delete();

        return response()->json(null, 204);
    }
}
