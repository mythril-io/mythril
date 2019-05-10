<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favourite;
use App\User;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    /**
     * Create a new FavouriteController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the Favourite.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Check if user has favourited a game.
     *
     * @param  int  $gID
     * @param  int  $uID
     * @return \Illuminate\Http\Response
     */
    public function checkUser($gID, $uID)
    {
        $userID = User::getID();
        if(!$userID === $uID) { return response()->json(['error' => 'Unauthorized to Retrieve Different User Favourite Entry'], 403); }

        $favourite = Favourite::where([
            ['game_id', $gID],
            ['user_id', $userID]
        ])->first();

        if(!$favourite) { return response()->json(['error' => "Favourite Doesn't Exist for the Authenticated User"], 404); }

        return response()->json($favourite, 200);
    }

    /**
     * Show the form for creating a new Favourite.
     *
     * @param  int  $gid
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Favourite in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 401); }

        //Check if User already has 10 favourites
        $userFavourites = Favourite::where([
            ['user_id', $userID]
        ])->count();
        if($userFavourites >= 10) { return response()->json(['error' => 'You Already Have 10 Game Favourites'], 403); }

        $this->validate($request, [
            'release_id' => 'required'
        ]);

        $existingFavourite = Favourite::withTrashed()->where([
            ['game_id', $request->game_id],
            ['user_id', $userID]
        ])->first();

        if($existingFavourite) 
        {
            if($existingFavourite->deleted_at) 
            {
                $existingFavourite->restore();
                    $existingFavourite->release_id = $request->release_id;
                $existingFavourite->save();

                return response()->json($existingFavourite, 201);
            }
            else { return response()->json(['error' => 'Favourite Already Exists'], 403); } 
        }
        else 
        {
            $newFavourite = Favourite::create([
                'game_id' => $request->game_id,
                'release_id' => $request->release_id,
                'user_id' => $userID
            ]);
        }

        return response()->json($newFavourite, 201);
    }

    /**
     * Display the specified Favourite.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Favourite.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Favourite in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 401); }

        $this->validate($request, [
            'release_id' => 'required'
        ]);

        $existingFavourite = Favourite::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingFavourite) { return response()->json(['error' => "Favourite Doesn't Exist for the Authenticated User"], 404); }

        $existingFavourite->release_id = $request->release_id;
        $existingFavourite->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified Favourite from storage.
     *
     * @param  int  $uID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 401); }

        $favourite = Favourite::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if($favourite) {
            $favourite->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Unauthorized to Remove Favourite'], 403);     
    }
}
