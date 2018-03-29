<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library;
use App\User;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * Create a new LibraryController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'userIndex', 'gameEntries', 'userEntriesGame']]);
    }

    /**
     * Display a listing of the LibraryEntry.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the LibraryEntry.
     *
     * @param  int  $gID
     * @return \Illuminate\Http\Response
     */
    public function gameEntries($gID)
    {
        $playingCount = Library::where([
            ['game_id', $gID], ['play_status_id', 1]
        ])->count();

        $planToPlayCount = Library::where([
            ['game_id', $gID],['play_status_id', 2]
        ])->count();

        $completedCount = Library::where([
            ['game_id', $gID], ['play_status_id', 3]
        ])->count();

        $onHoldCount = Library::where([
            ['game_id', $gID], ['play_status_id', 4]
        ])->count();

        $droppedCount = Library::where([
            ['game_id', $gID], ['play_status_id', 5]
        ])->count();

        $countArray = array(
            "playing"=>$playingCount, 
            "planToPlay"=>$planToPlayCount, 
            "completed"=>$completedCount,
            "onHold"=>$onHoldCount,
            "dropped"=>$droppedCount
        );

        return response()->json($countArray, 200);
    }

    /**
     * Return user library entries for specified game.
     *
     * @param  int  $gID
     * @param  int  $uID
     * @return \Illuminate\Http\Response
     */
    public function userEntriesGame($gID, $uID)
    {
        $entries = Library::where([
            ['game_id', $gID],
            ['user_id', $uID]
        ])->with([
        'playStatus',
        'release' => function($q) {$q->with(['platform', 'publisher']);}
        ])->get();

        if($entries->isEmpty()) { return response()->json(['error' => "No Library Entries for the Authenticated User"], 404); }

        return response()->json($entries, 200);
    }

    /**
     * Show the form for creating a new LibraryEntry.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created LibraryEntry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userID = User::getID();
        if(!$userID === $request->user_id) { return response()->json(['error' => 'Unauthorized to Store Library Entry'], 403); }

        $this->validate($request, [
            'release_id' => 'required|numeric|min:1',
            'play_status_id' => 'required|numeric|min:1',
            'own' => 'boolean',
            'digital' => 'boolean',
            'score' => 'nullable|digits_between:1,10',
            'hours' => 'nullable|numeric|min:1',
            'notes' => 'nullable|alpha_num'
        ]);

        $existingEntry = Library::withTrashed()->where([
            ['user_id', $userID],
            ['game_id', $request->game_id],
            ['release_id', $request->release_id]
        ])->first();

        if($existingEntry) 
        {
            if($existingEntry->deleted_at) 
            {
                $existingEntry->restore();
                    $existingEntry->play_status_id = $request->play_status_id;
                    $existingEntry->score = $request->score;
                    $existingEntry->own = $request->own;
                    $existingEntry->digital = $request->digital;
                    $existingEntry->hours = $request->hours;
                    $existingEntry->notes = $request->notes;
                $existingEntry->save();

                $existingEntry = $existingEntry->load([
                    'playStatus',
                    'release' => function($q) {$q->with(['platform']);}
                ]);

                return response()->json($existingEntry, 201);
            }
            else { return response()->json(['error' => 'Library Entry Already Exists'], 403); } 
        }
        else 
        {
            $newEntry = Library::create([
                'user_id' => $userID,
                'game_id' => $request->game_id,
                'release_id' => $request->release_id,
                'play_status_id' => $request->play_status_id,
                'score' => $request->score,
                'own' => $request->own,
                'digital' => $request->digital,
                'hours' => $request->hours,
                'notes' => $request->notes
            ]);
        }
        $newEntry = $newEntry->load([
            'playStatus',
            'release' => function($q) {$q->with(['platform']);}
        ]);

        return response()->json($newEntry, 201);
    }

    /**
     * Display the specified LibraryEntry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified LibraryEntry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified LibraryEntry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $this->validate($request, [
            'play_status_id' => 'required|numeric|min:1',
            'own' => 'boolean',
            'digital' => 'boolean',
            'score' => 'nullable|digits_between:1,10',
            'hours' => 'nullable|numeric|min:1',
            'notes' => 'nullable|alpha_num'
        ]);

        $existingEntry = Library::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if(!$existingEntry) { return response()->json(['error' => "Library Entry Doesn't Exist for the Authenticated User"], 404); }
            $existingEntry->play_status_id = $request->play_status_id;
            $existingEntry->score = $request->score;
            $existingEntry->own = $request->own;
            $existingEntry->digital = $request->digital;
            $existingEntry->hours = $request->hours;
            $existingEntry->notes = $request->notes;
        $existingEntry->save();

        $existingEntry = $existingEntry->load([
            'playStatus',
            'release' => function($q) {$q->with(['platform']);}
        ]);

        return response()->json($existingEntry, 200);
    }

    /**
     * Remove the specified LibraryEntry from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userID = User::getID();
        if(!$userID) { return response()->json(['error' => 'Unauthorized, Please Login'], 403); }

        $libraryEntry = Library::where([
            ['id', $id],
            ['user_id', $userID]
        ])->first();

        if($libraryEntry) {
            $libraryEntry->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Unauthorized to Remove Library Entry'], 403);
    }
}
