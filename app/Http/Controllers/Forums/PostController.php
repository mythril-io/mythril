<?php

namespace App\Http\Controllers\Forums;

use App\Forums\Post;
use App\Forums\Discussion;
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
        $this->middleware('auth:api', ['except' => ['index', 'show', 'find']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $discussion_id
     * @return \Illuminate\Http\Response
     */
    public function index(int $discussion_id)
    {
        $posts = Post::with([
            'user',
            'parent' => function($q) {$q->with([
                    'user', 
                    'parent' => function($q) {$q->with(['user']);}  
                ]);},
            'replies' => function($q) {$q->with(['user']);}
          ])->where('discussion_id', $discussion_id)->paginate(25);

        $num = ($posts->currentPage() - 1) * $posts->perPage() + 1;

        foreach($posts as $post) {
            $post->num = $num++;
        }

        return $posts;
    }

    /**
     * Return a paginated set of Posts.
     *
     * @param  Int  $discussion_id
     * @param  Int  $post_id
     * @return \Illuminate\Http\Response
     */
    public function find(int $discussion_id, int $post_id) {
        
        $position= Post::where([
            ['id', '<=', $post_id],
            ['discussion_id', '=', $discussion_id],
        ])->count(); // for example 601

        $page = ceil($position/25);

        // return $page;

        $posts = Post::with([
            'user',
            'parent' => function($q) {$q->with([
                    'user', 
                    'parent' => function($q) {$q->with(['user']);}  
                ]);},
            'replies' => function($q) {$q->with(['user']);}
          ])->where('discussion_id', $discussion_id)->paginate(25, ['*'], 'page', $page);

        $num = ($posts->currentPage() - 1) * $posts->perPage() + 1;

        foreach($posts as $post) {
            $post->num = $num++;
        }

        return $posts;        
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
            'discussion_id' => 'required|numeric',
            'parent_post_id' => 'nullable|numeric',
        ]);

        //Create the Post
        $post = Post::create([
            'body' => $request->body,
            'user_id' => $userID,
            'discussion_id' => $request->discussion_id,
            'parent_post_id' => $request->parent_post_id
        ]);

        //Eager load the Post, with the associated User
        $post->load(['user']);

        $discussion = Discussion::find($request->discussion_id);
            $discussion->increment('post_count', 1);
            $discussion->user_count = $discussion->posts->groupBy('user_id')->count();
            $discussion->last_post_id = $post->id;
            $discussion->last_posted_at = $post->created_at;
        $discussion->save();

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Edit Post'], 401); }

        $this->validate($request, [
            'body' => 'required|string|min: 10',
        ]);

        $existingPost = Post::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingPost) { return response()->json(['error' => "Post Doesn't Exist for the Authenticated User"], 404); }
            $existingPost->body = $request->body;
            $existingPost->edit_count++;
        $existingPost->save();

        return response()->json(null, 204);
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

    /**
     * Like the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleLike($id)
    {
        $user = User::get();
        if(!$user) { return response()->json(['error' => 'Unauthorized to Like'], 401); }

        $post = Post::find($id);
        if(!$post) { return response()->json(['error' => 'Post not Found'], 404); }

        $user->toggleLike($post);

        $status = $post->isLikedBy($user);

        // if($status) {
        //     $post->increment('like_count', 1);
        // } else {
        //     $post->decrement('like_count', 1);
        // }
        $post->save();

        return response()->json($status, 200);
    }
}
