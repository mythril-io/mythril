<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wishlist;
use App\User;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Create a new WishlistController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the Wishlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Check if user has a game in their wishlist.
     *
     * @param  int  $gID
     * @param  int  $uID
     * @return \Illuminate\Http\Response
     */
    public function checkUser($gID, $uID)
    {
        $userID = User::getID();
        if(!$userID === $uID) { return response()->json(['error' => 'Unauthorized to Retrieve Different User Wishlist Entry'], 403); }

        $wishlistEntry = Wishlist::where([
            ['game_id', $gID],
            ['user_id', $userID]
        ])->first();

        if(!$wishlistEntry) { return response()->json(['error' => "Wishlist Entry Doesn't Exist for the Authenticated User"], 404); }

        return response()->json($wishlistEntry, 200);
    }

    /**
     * Show the form for creating a new Wishlist Item.
     *
     * @param  int  $gid
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Wishlist Entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 401); }

        $this->validate($request, [
            'release_id' => 'required'
        ]);

        $existingWishlistEntry = Wishlist::withTrashed()->where([
            ['game_id', $request->game_id],
            ['user_id', $userID]
        ])->first();

        if($existingWishlistEntry) 
        {
            if($existingWishlistEntry->deleted_at) 
            {
                $existingWishlistEntry->restore();
                    $existingWishlistEntry->release_id = $request->release_id;
                $existingWishlistEntry->save();

                return response()->json($existingWishlistEntry, 201);
            }
            else { return response()->json(['error' => 'Already Exists in Wishlist'], 403); } 
        }
        else 
        {
            $newWishlistEntry = Wishlist::create([
                'game_id' => $request->game_id,
                'release_id' => $request->release_id,
                'user_id' => $userID
            ]);
        }

        return response()->json($newWishlistEntry, 201);
    }

    /**
     * Display the specified Wishlist Entry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Wishlist Entry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Wishlist Entry in storage.
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

        $existingWishlistEntry = Wishlist::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingWishlistEntry) { return response()->json(['error' => "Wishlist Entry Doesn't Exist for the Authenticated User"], 404); }

        $existingWishlistEntry->release_id = $request->release_id;
        $existingWishlistEntry->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified Wishlist Entry from storage.
     *
     * @param  int  $uID
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 401); }

        $wishlistEntry = Wishlist::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if($wishlistEntry) {
            $wishlistEntry->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Unauthorized to Remove Wishlist Entry'], 403);

        
    }
}
