<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Create a new GameController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::orderBy('created_at', 'desc')->paginate(50);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userID = User::getID();

        $user = User::with([
            // 'reviews' => function($q) {$q->with([
            //     'release' => function($q) {$q->with(['platform']);},
            //     'game',
            //     'user'
            // ]);},
            // 'recommendations' => function($q) {$q->with([
            //     'game',
            //     'release' => function($q) {$q->with(['platform']);},
            //     'secondGame',
            //     'secondRelease' => function($q) {$q->with(['platform']);}
            // ]);},
            'favourites' => function($q) {$q->with(['game', 'release']);},
            'libraries' => function($q) {$q->with([
                'game' => function($q) {$q->with(['genres']);}, 
                'playStatus',
                'release' => function($q) {$q->with(['platform', 'publisher', 'region']);} 
            ]);}
        ])->withCount('reviews', 'recommendations', 'libraries', 'followers', 'following')->where('id', '=', $id)->first();

        if(!$user) { return response()->json(['error' => "User Doesn't Exist"], 404); }

        if(!$userID || $userID !== $user->id) { $userOwns = false; }
        else if($userID === $user->id) { $userOwns = true; }

        return response()->json(array('user'=>$user, 'userOwns'=> $userOwns), 200);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to View Settings. Please Login.'], 401); }
        else if($userID != $id) { return response()->json(['error' => 'Unauthorized to View Settings. Please Login.'], 403); }

        $this->validate($request, [
            'about_me' => 'string|nullable',
            'timezone' => 'timezone',
            'birthday' => 'date|nullable',
            'gender' => 'alpha|nullable',
            'location' => 'string|max: 150|nullable'
        ]);

        $existingUser = User::where('id', $userID)->first();
        if(!$existingUser) { return response()->json(['error' => "User Doesn't Exist"], 404); }

        if($request->update == "profile") {
            $existingUser->about_me = $request->about_me;
            $existingUser->timezone = $request->timezone;
            $existingUser->birthday = $request->birthday;
            $existingUser->gender = $request->gender;
            $existingUser->location = $request->location;
        }
        else if($request->update == "images") {
            if($request->get('avatar')) {
                $avatar = Image::make($request->get('avatar'));
                $avatar->resize(300, null, function ($constraint) { $constraint->aspectRatio(); });
                $avatar->encode('png', 80);
                
                //Avatar
                $avatarExtension = explode('/', $avatar->mime() )[1];
                $avatarName = $existingUser->id.'.'.$avatarExtension;
                $avatar = $avatar->stream(); 
                Storage::disk('spaces')->put("users/avatars/$avatarName", (string) $avatar, 'public');

                $existingUser->avatar = $avatarName;
            }

            if($request->get('banner')) {
                $banner = Image::make($request->get('banner'));
                $banner->encode('jpg', 80);

                //Banner
                $bannerExtension = explode('/', $banner->mime() )[1];
                $bannerName = $existingUser->id.'.'.$bannerExtension;
                $banner = $banner->stream();
                Storage::disk('spaces')->put("users/banners/$bannerName", (string) $banner, 'public');
               
                $existingUser->banner = $bannerName;
            }
            
        }
        else if($request->update == "deleteAvatar") {
            Storage::disk('spaces')->delete("users/avatars/$existingUser->avatar");
            $existingUser->avatar = null;
        }
        else if($request->update == "deleteBanner") {
            Storage::disk('spaces')->delete("users/banners/$existingUser->banner");
            $existingUser->banner = null;
        }
        else if($request->update == "social") {
            $existingUser->twitter = $request->twitter;
            $existingUser->facebook = $request->facebook;
            $existingUser->instagram = $request->instagram;
            $existingUser->discord = $request->discord;
            $existingUser->deviant_art = $request->deviant_art;
            $existingUser->reddit = $request->reddit;
            $existingUser->patreon = $request->patreon;
            $existingUser->tumblr = $request->tumblr;

            $existingUser->youtube = $request->youtube;
            $existingUser->twitch = $request->twitch;
            $existingUser->battle_net = $request->battle_net;
            $existingUser->steam = $request->steam;
            $existingUser->playstation = $request->playstation;
            $existingUser->nintendo_switch = $request->nintendo_switch;
            $existingUser->xbox = $request->xbox;
        }
        else if($request->update == "password") {
            $existingUser->password = bcrypt($request->new_password);
        }

        $existingUser->save();
        return response()->json($existingUser, 200);
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
