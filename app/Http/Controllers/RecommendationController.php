<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recommendation;
use App\User;

class RecommendationController extends Controller
{
    /**
     * Create a new RecommendationController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'userIndex', 'gameIndex', 'show']]);
    }

    /**
     * Display a paginated set of Recommendations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Recommendation::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);},
            'secondGame',
            'secondRelease' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->orderBy('created_at', 'desc')->paginate(8);
    }

    /**
     * Display a paginated set of Recommendations for a specified User.
     *
     * @return \Illuminate\Http\Response
     */
    public function userIndex($id)
    {
        return Recommendation::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);},
            'secondGame',
            'secondRelease' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->orderBy('created_at', 'desc')->where('user_id', $id)->paginate(8);
    }

    /**
     * Display a paginated set of Recommendations for a specified Game.
     *
     * @return \Illuminate\Http\Response
     */
    public function gameIndex($id)
    {
        return Recommendation::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);},
            'secondGame',
            'secondRelease' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->orderBy('created_at', 'desc')->where('game_id', $id)->paginate(8);
    }

    /**
     * Show the form for creating a new Recommendation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Recommendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Create Recommendation. Please Login.'], 403); }

        $this->validate($request, [
            'game_id' => 'required',
            'release_id' => 'required',            
            'second_game_id' => 'required',
            'second_release_id' => 'required',
            'content' => 'required|min: 200',
        ]);

        $existingRecommendation = Recommendation::where([
            ['game_id', $request->game_id],
            ['release_id', $request->release_id],
            ['second_game_id', $request->second_game_id],
            ['second_release_id', $request->second_release_id],
            ['user_id', $userID]
        ])->first();

        if($existingRecommendation) 
        {
            return response()->json(['error' => 'Recommendation Already Exists'], 403); 
        }
        else 
        {
            $newRecommendation = Recommendation::create([
                'game_id' => $request->game_id,
                'release_id' => $request->release_id,
                'second_game_id' => $request->second_game_id,
                'second_release_id' => $request->second_release_id,
                'user_id' => $userID,
                'content' => $request->content,
            ]);
        }

        return response()->json($newRecommendation, 201);
    }

    /**
     * Display the specified Recommendation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userID = User::getID();

        $recommendation = Recommendation::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);},
            'secondGame',
            'secondRelease' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->where('id', '=', $id)->first();

        if(!$recommendation) { return response()->json(['error' => "Recommendation Doesn't Exist"], 404); }

        if(!$userID || $userID !== $recommendation->user->id) { $userOwns = false; }
        else if($userID === $recommendation->user->id) { $userOwns = true; }

        return response()->json(array('recommendation'=>$recommendation, 'userOwns'=> $userOwns), 200);
    }

    /**
     * Show the form for editing the specified Recommendation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Recommendation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Edit Recommendation'], 403); }

        $this->validate($request, [
            'content' => 'required|min: 200'
        ]);

        $existingRecommendation = Recommendation::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingRecommendation) { return response()->json(['error' => "Recommendation Doesn't Exist for the Authenticated User"], 404); }

            $existingRecommendation->content = $request->content;
        $existingRecommendation->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified Recommendation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $recommendation = Recommendation::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if($recommendation) {
            $recommendation->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Unauthorized to Remove Recommendation'], 403);  
    }
}
