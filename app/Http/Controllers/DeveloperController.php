<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Developer;

class DeveloperController extends Controller
{
    /**
     * Create a new DeveloperController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Return all Developers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Developer::all();
    }

    /**
     * Show the form for creating a new Developer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Developer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:developers',
            'country' => 'required',
            'description' => 'required'
        ]);

        //Create the Developer
        $developer = Developer::create([
            'name' => $request->name,
            'country' => $request->country,
            'description' => $request->description
        ]);

        return response()->json($developer, 201);
    }

    /**
     * Display the specified Developer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Developer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Developer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:developers,name,'.$id,
            'country' => 'required',
            'description' => 'required'
        ]);
        $developer = Developer::find($id);
            $developer->update($request->all());
        $developer->save();

        return response()->json($developer, 200);
    }

    /**
     * Remove the specified Developer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $developer = Developer::find($id);
        $developer->delete();

        return response()->json(null, 204);
    }
}
