<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publisher;

class PublisherController extends Controller
{
    /**
     * Create a new PublisherController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Return all Publishers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Publisher::all();
    }

    /**
     * Show the form for creating a new Publisher.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Publisher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:publishers',
            'country' => 'required',
            'description' => 'required'
        ]);

        //Create the Publisher
        $publisher = Publisher::create([
            'name' => $request->name,
            'country' => $request->country,
            'description' => $request->description
        ]);

        return response()->json($publisher, 201);
    }

    /**
     * Display the specified Publisher.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Publisher.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Publisher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:publishers,name,'.$id,
            'country' => 'required',
            'description' => 'required'
        ]);
        $publisher = Publisher::find($id);
            $publisher->update($request->all());
        $publisher->save();

        return response()->json($publisher, 200);
    }

    /**
     * Remove the specified Publisher from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publisher = Publisher::find($id);
        $publisher->delete();

        return response()->json(null, 204);
    }
}
