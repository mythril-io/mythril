<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\User;

class ReviewController extends Controller
{
    /**
     * Create a new ReviewController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'userIndex', 'gameIndex', 'show']]);
    }

    /**
     * Return a paginated set of Reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Review::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->orderBy('created_at', 'desc')->paginate(8);
    }

    /**
     * Return a paginated set of Reviews for a specified User.
     *
     * @return \Illuminate\Http\Response
     */
    public function userIndex($id)
    {
        return Review::with([
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->orderBy('created_at', 'desc')->where('user_id', $id)->paginate(8);
    }

    /**
     * Return a paginated set of Reviews for a specified Game.
     *
     * @return \Illuminate\Http\Response
     */
    public function gameIndex($id)
    {
        return Review::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->orderBy('created_at', 'desc')->where('game_id', $id)->paginate(8);
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
     * Store a newly created Review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Create Review. Please Login.'], 401); }

        $this->validate($request, [
            'game_id' => 'required',
            'release_id' => 'required',
            'summary' => 'required|min: 60|max: 255',
            'content' => 'required|min: 500',
            'score' => 'numeric|between:1,100|required'
        ]);

        $existingReview = Review::where([
            ['game_id', $request->game_id],
            ['release_id', $request->release_id],
            ['user_id', $userID]
        ])->first();

        if($existingReview) 
        {
            return response()->json(['error' => 'Review Already Exists'], 403); 
        }
        else 
        {
            $newReview = Review::create([
                'game_id' => $request->game_id,
                'release_id' => $request->release_id,
                'user_id' => $userID,
                'summary' => $request->summary,
                'content' => $request->content,
                'score' => $request->score
            ]);
        }

        return response()->json($newReview, 201);
    }

    /**
     * Display the specified Review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userID = User::getID();

        $review = Review::with([
            'user',
            'game',
            'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->where('id', '=', $id)->first();

        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        if(!$userID || $userID !== $review->user->id) { $userOwns = false; }
        else if($userID === $review->user->id) { $userOwns = true; }

        return response()->json(array('review'=>$review, 'userOwns'=> $userOwns), 200);
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
     * Update the specified Review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Edit Review'], 401); }

        $this->validate($request, [
            'summary' => 'required|min: 60|max: 255',
            'content' => 'required|min: 500',
            'score' => 'numeric|between:1,100|required'
        ]);

        $existingReview = Review::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingReview) { return response()->json(['error' => "Review Doesn't Exist for the Authenticated User"], 404); }

            $existingReview->summary = $request->summary;
            $existingReview->content = $request->content;
            $existingReview->score = $request->score;
        $existingReview->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 401); }

        $review = Review::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if($review) {
            $review->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Unauthorized to Remove Review'], 403);  
    }

    public function likeReview(Request $request)
    {
        $user = User::get();
        if(!$user) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $review = Review::find($request->review_id);
        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        if($user->hasUpvoted($review)) {
            $user->cancelVote($review);
        }
        else {
            $user->upvote($review);
        }
            
        return response()->json([
            'likes' => $review->like_count,
            'dislikes' => $review->dislike_count,
            'userLikes' => $user->hasUpvoted($review),
            'userDislikes' => $user->hasDownvoted($review)
        ], 200);
    }

    public function dislikeReview(Request $request)
    {
        $user = User::get();
        if(!$user) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $review = Review::find($request->review_id);
        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        if($user->hasDownvoted($review)) {
            $user->cancelVote($review);
        }
        else {
            $user->downvote($review);
        }

        return response()->json([
            'likes' => $review->like_count,
            'dislikes' => $review->dislike_count,
            'userLikes' => $user->hasUpvoted($review),
            'userDislikes' => $user->hasDownvoted($review)
        ], 200);
    }

    public function checkUserReview($id)
    {
        $user = User::get();
        if(!$user) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $review = Review::find($id);
        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        return response()->json([
            'userLikes' => $user->hasUpvoted($review),
            'userDislikes' => $user->hasDownvoted($review)
        ], 200);
    }
}
