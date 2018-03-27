<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Platform;

class PlatformController extends Controller
{
    /**
     * Create a new PlatformController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Return a paginated set of Platforms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Platform::paginate(10);
    }

    /**
     * Show the form for creating a new Platform.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Platform in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:platforms',
            'acronym' => ''
        ]);

        //Create the Platform
        $platform = Platform::create([
            'name' => $request->name,
            'acronym' => $request->acronym
        ]);

        return response()->json($platform, 201);
    }

    /**
     * Display the specified Platform.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Platform.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Platform in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate Request
        $this->validate($request, [
            'name' => 'required|unique:platforms,name,'.$id,
        ]);
        $platform = Platform::find($id);
            $platform->update($request->all());
        $platform->save();

        return response()->json($platform, 200);
    }

    /**
     * Remove the specified Platform from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $platform = Platform::find($id);
        $platform->delete();

        return response()->json(null, 204);
    }
}
