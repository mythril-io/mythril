<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Release;
Use DB;

class ReleaseController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->middleware('role:admin', ['except' => ['index']]);
    }
    
    /**
     * Display a listing of the Release.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new Release.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Release in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified Release.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified Release in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $this->validate($request,[
           'releases' => 'required',
       ]);

        DB::table('releases')
            ->where('id', $id)
            ->update([
                'platform_id' => array_get($request, 'release.platform.id'),
                'publisher_id' => array_get($request, 'release.publisher.id'),
                'codeveloper_id' => array_get($request, 'release.codeveloper.id', null),
                'alternate_title' => array_get($request, 'release.altTitle'),
                'NA' => array_get($request, 'release.NA'),
                'EU' => array_get($request, 'release.EU'),
                'JP' => array_get($request, 'release.JP'),
                'WW' => array_get($request, 'release.WW')
            ]);

        return response()->json(null, 200);
    }

    /**
     * Update the Releases in storage for the specified Game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGame(Request $request, $id)
    {
       $this->validate($request,[
           'releases' => 'required',
       ]);

       $game = Game::find($id);
         $game->addReleases($request->releases, $id);
       $game->save();

       return response()->json(null, 200);
    }

    /**
     * Remove the specified Release from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $release = Release::find($request->id);
        $release->delete();

        return response()->json(null, 204);
    }
}
