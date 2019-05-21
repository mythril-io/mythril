<?php

namespace App\Http\Controllers\Forums;

use App\Forums\Discussion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Input;
use App\Filters\ForumFilters;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DiscussionController extends Controller
{
    /**
     * Create a new DiscussionController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'gameIndex', 'show']]);
        // $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $tag
     * @param  ForumFilters $filters
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index($tag = NULL, ForumFilters $filters, Request $request)
    {
        $subscribed = $request->input('subscribed');
        $user = User::get();

        if($subscribed==='true' && $user) {
            //Retrive User Subscribed Discussions
            return $this->getUserSubscriptions($tag, $filters);
        } else {
            //Retrive Discussions
            return Discussion::with([
                'user',
                'tags',
                'games',
                'lastPost' => function($q) {$q->with(['user']);}
            ])->when($tag, function ($query, $tag) {
                return $query->whereHas('tags', function ($query) use ($tag) {
                        $query->where('slug', '=', $tag);
                    });
            })->filter($filters)->orderBy('last_posted_at', 'desc')->paginate(15);

        }
    }

    /**
     * Return a paginated set of Discussions for a specified Game.
     *
     * @return \Illuminate\Http\Response
     */
    public function gameIndex($id)
    {
        return Discussion::with([
            'user',
            'tags',
            'games',
            'lastPost' => function($q) {$q->with(['user']);}
        ])->whereHas('games', function ($query) use ($id) {
            $query->where('id', '=', $id);
        })->orderBy('last_posted_at', 'desc')->paginate(15);
    }

    /**
     * Display a listing of the subscribed resources.
     *
     * @param  string  $tag
     * @param  ForumFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function getUserSubscriptions($tag = NULL, ForumFilters $filters) {
        return User::get()->subscriptions(Discussion::class)->with([
                'user',
                'tags',
                'games',
                'lastPost' => function($q) {$q->with(['user']);}
            ])->when($tag, function ($query, $tag) {
                return $query->whereHas('tags', function ($query) use ($tag) {
                        $query->where('slug', '=', $tag);
                    });
            })->filter($filters)->orderBy('created_at', 'desc')->paginate(15);
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
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Create Discussion. Please Login.'], 401); }

        // $tagCheck = Arr::first($request->selectedTags, function ($value, $key) {
        //     return $value.slug == 'site-updates';
        // });

        // if(userId != 1 && (count(tagCheck)==1) ) {
        //     return response()->json(['error' => 'Unauthorized to Create Discussion for Specified Tag'], 403);
        // }

        //Validate Request
        $this->validate($request, [
            'title' => 'required|string|min: 10',
            'body' => 'required|string|min: 10',
            'selectedTags' => 'required'
        ]);

        //Create the Discussion
        $discussion = Discussion::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $userID,
            'slug' => Str::slug($request->title),
            'last_posted_at' => Carbon::now()->toDateTimeString()
        ]);

        //Attach Tags
        $tagIds = collect($request->selectedTags)->pluck(['id']);

        // if($request->selectedChildTags) {
        //     $childTagIds = collect($request->selectedChildTags)->pluck(['id']);
        //     $tagIds = $tagIds->merge($childTagIds);
        // }

        $discussion->tags()->sync($tagIds->all()); //sync: Any IDs that are not in the given array will be removed from the intermediate table

        //Attach Games if applicable
        if($request->selectedGames) {
            $games = collect($request->selectedGames)->pluck(['id'])->all();
            $discussion->games()->sync($games);
        }

        //Eager load the discussion, with the associated User, Tags, Games
        $discussion->load(['user', 'tags', 'games']);

        return response()->json($discussion, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userID = User::getID();

        $discussion = Discussion::with([
            'user',
            'tags',
            'games'
            // 'release' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
        ])->where('id', '=', $id)->first();

        if(!$discussion) { 
            return response()->json(['error' => "Discussion Doesn't Exist"], 404); 
        }
        else {
            $discussion->view_count += 1;
            $discussion->save();
        }

        if(!$userID || $userID !== $discussion->user->id) { $userOwns = false; }
        else if($userID === $discussion->user->id) { $userOwns = true; }

        return response()->json(array('discussion'=>$discussion, 'userOwns'=> $userOwns), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Forums\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function edit(Discussion $discussion)
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
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Edit Discussion'], 401); }

        $this->validate($request, [
            'body' => 'required|string|min: 10',
        ]);

        $existingDiscussion = Discussion::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingDiscussion) { return response()->json(['error' => "Discussion Doesn't Exist for the Authenticated User"], 404); }
            $existingDiscussion->body = $request->body;
            $existingDiscussion->edit_count++;
            $existingDiscussion->edited_at = Carbon::now()->toDateTimeString();
        $existingDiscussion->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $discussion = Discussion::find($id);
        $discussion->delete();

        return response()->json(null, 204);
    }

    /**
     * Subscribe to the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleSubscribe($id)
    {
        $user = User::get();
        if(!$user) { return response()->json(['error' => 'Unauthorized to Subscribe'], 401); }

        $discussion = Discussion::find($id);
        if(!$discussion) { return response()->json(['error' => 'Discussion not Found'], 404); }

        $user->toggleSubscribe($discussion);
        $status = $discussion->isSubscribedBy($user);

        return response()->json($status, 200);
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

        $discussion = Discussion::find($id);
        if(!$discussion) { return response()->json(['error' => 'Discussion not Found'], 404); }

        $user->toggleLike($discussion);

        $status = $discussion->isLikedBy($user);

        if($status) {
            $discussion->increment('like_count', 1);
        } else {
            $discussion->decrement('like_count', 1);
        }
        $discussion->save();

        return response()->json($status, 200);
    }
}
