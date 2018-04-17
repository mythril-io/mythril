<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\User;
use App\Review;
use App\Recommendation;
use App\Library;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Stats - games_count, users_count, reviews_count, recommendations_count
        $games_count = Game::all()->count();
        $users_count = User::all()->count();
        $reviews_count = Review::all()->count();
        $recommendations_count = Recommendation::all()->count();

        //Recent User Activity
        $recent_user_activity = Library::with([
            'user',
            'game',
            'playStatus',
            'release' => function($q) {$q->with(['platform']);}
        ])->orderBy('created_at', 'desc')->limit(6)->get();

        //Top 5 rated games
        $ranked = Game::orderByRaw('-score_rank desc')->limit(5)->get();
        //dd($ranked);

        //Top 5 popular games
        $popular = Game::orderByRaw('-popularity_rank desc')->limit(5)->get();

        //Top 6 trending games
        $trending = Game::orderBy('trending_page_views', 'desc')->orderByRaw('-popularity_rank desc')->limit(6)->get();

        return response()->json(array(
            'ranked' => $ranked, 
            'popular' => $popular,
            'trending' => $trending,
            'games_count' => $games_count,
            'users_count' => $users_count,
            'reviews_count' => $reviews_count,
            'recommendations_count' => $recommendations_count,
            'recent_user_activity' => $recent_user_activity
        ), 200);
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
