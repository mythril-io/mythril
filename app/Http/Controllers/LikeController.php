<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Review;

class LikeController extends Controller
{
    /**
     * Create a new LikeController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function likeReview(Request $request)
    {
        $user = Auth::user();
        if(!$user) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $review = Review::find($request->review_id);
        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        $user->toggleLike($review);

        return response()->json([
            'likes' => $review->upvoters->count(),
            'dislikes' => $review->downvoters->count(),
            'userLikes' => $user->hasLiked($review),
            'userDislikes' => $user->hasDisliked($review)
        ], 200);
    }

    public function dislikeReview(Request $request)
    {
        $user = Auth::user();
        if(!$user) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $review = Review::find($request->review_id);
        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        $user->toggleDislike($review);

        return response()->json([
            'likes' => $review->upvoters->count(),
            'dislikes' => $review->downvoters->count(),
            'userLikes' => $user->hasLiked($review),
            'userDislikes' => $user->hasDisliked($review)
        ], 200);
    }

    public function checkUserReview($id)
    {
        $user = Auth::user();
        if(!$user) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $review = Review::find($id);
        if(!$review) { return response()->json(['error' => "Review Doesn't Exist"], 404); }

        return response()->json([
            'userLikes' => $user->hasUpvoted($review),
            'userDislikes' => $user->hasDownvoted($review)
        ], 200);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
