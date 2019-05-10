<?php

namespace App\Http\Controllers\Forums;

use App\Forums\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class PostController extends Controller
{
    /**
     * Create a new DiscussionController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $discussion_id
     * @return \Illuminate\Http\Response
     */
    public function index(int $discussion_id)
    {
        return Post::with([
            'user'
          ])->where('discussion_id', $discussion_id)->paginate(20);
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
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Create Post. Please Login.'], 401); }

        //Validate Request
        $this->validate($request, [
            'body' => 'required|string|min: 10',
            'discussion_id' => 'required'
        ]);

        //Create the Post
        $post = Post::create([
            'body' => $request->body,
            'user_id' => $userID,
            'discussion_id' => $request->discussion_id
        ]);

        //Eager load the Post, with the associated User
        $post->load(['user']);

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Forums\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Forums\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Forums\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Forums\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post = Post::find($post->id);
        $post->delete();

        return response()->json(null, 204);
    }
}
