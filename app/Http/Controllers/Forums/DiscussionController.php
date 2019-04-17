<?php

namespace App\Http\Controllers\Forums;

use App\Forums\Discussion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;

class DiscussionController extends Controller
{

    /**
     * Create a new DiscussionController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // $this->middleware('auth:api', ['except' => ['index']]);
        // $this->middleware('role:admin', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Discussion::with([
            'user',
            'tags',
            // 'categories' => function ($q) {
            //     $q->with('subcategories')->where('pivot_column', 'discussion_id');
            // }
        ])->orderBy('created_at', 'desc')->paginate(20);


        // $users = App\User::with(['posts' => function ($query) {
        //     $query->where('title', 'like', '%first%');
        // }])->get();
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
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Create Discussion. Please Login.'], 403); }

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
            'slug' => Str::slug($discussion->title)
        ]);

        //Attach Tags
        $tagIds = collect($request->selectedTags)->pluck(['id'])->all();

        if($request->selectedChildTags) {
            $childTagIds = collect($request->selectedChildTags)->pluck(['id'])->all();
            $tagIds = $tagIds->merge($childTagIds);
        }

        $discussion->tags()->sync($tagIds); //sync: Any IDs that are not in the given array will be removed from the intermediate table

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
     * @param  \App\Forums\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function show(Discussion $discussion)
    {
        //
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
     * @param  \App\Forums\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized to Edit Discussion'], 403); }

        $this->validate($request, [
            'body' => 'required|string|min: 10',
        ]);

        $existingDiscussion = Discussion::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingDiscussion) { return response()->json(['error' => "Discussion Doesn't Exist for the Authenticated User"], 404); }
            $existingDiscussion->body = $request->body;
        $existingDiscussion->save();

        DB::table('discussions')->where('id', $id)->increment('edit_count');

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Forums\Discussion  $discussion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discussion $discussion)
    {
        $discussion = Discussion::find($id);
        $discussion->delete();

        return response()->json(null, 204);
    }
}
