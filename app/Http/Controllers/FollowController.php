<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Create a new FollowController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'showFollowing', 'showFollowers']]);
    }

    /**
     * Check if User is following specified User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkUser($id)
    {
        $user = User::find($id);
        $authUserID = User::getID();
        if(!$user) { return response()->json(['error' => 'User Does Not Exist'], 404); }

        $status = $user->followers()->where('follower_id', $authUserID)->count();
        return response()->json(['following' => $status], 200);
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Follow the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {   
        $user = User::find($id);
        $authUserID = User::getID();
        if(!$user) { return response()->json(['error' => 'User Does Not Exist'], 404); }
        if($id == $authUserID) { return response()->json(['error' => 'Cannot Follow Self'], 403); }

        $user->followers()->attach($authUserID);

        return response()->json(['success' => 'Successfully Followed the User.'], 200);
    }

    /**
     * Display the User Following.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showFollowing($id)
    {
        $user = User::find($id);
        if(!$user) { return response()->json(['error' => 'User Does Not Exist'], 404); }

        $following = $user->following;

        return response()->json([
            'following' => $following
        ], 200);
    }

    /**
     * Display the User Followers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showFollowers($id)
    {
        $user = User::find($id);
        if(!$user) { return response()->json(['error' => 'User Does Not Exist'], 404); }

        $followers = $user->followers;

        return response()->json([
            'followers' => $followers,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Unfollow the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $authUserID = User::getID();
        if(!$user) { return response()->json(['error' => 'User Does Not Exist'], 404); }
        if($id == $authUserID) { return response()->json(['error' => 'Cannot Unfollow Self'], 403); }

        $user->followers()->detach($authUserID);

        return response()->json(['success' => 'Successfully unfollowed the User.'], 200);
    }
}
