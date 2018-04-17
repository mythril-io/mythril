<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Game;
use App\Filters\GameFilters;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;


class GameController extends Controller
{
    /**
     * Create a new GameController instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('role:admin', ['except' => ['index', 'show']]);
    }

    /**
     * Return a paginated set of Games with applicable filters.
     *
     * @param  GameFilters $filters
     * @return Collection
     */
    public function index(GameFilters $filters)
    {
      return Game::with([
        'genres',
        'developer',
        // 'user',
        // 'releases' => function($q) {$q->with(['platform', 'publisher', 'codeveloper']);}
      ])->filter($filters)->orderByRaw('-popularity_rank desc')->paginate(20);
    }

    /**
     * Return all Games, eager loaded with User, Genres, Developer and Releases.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
      return Game::with([
        'user',
        'genres',
        'developer',
        'releases' => function($q) {$q->with(['platform', 'publisher', 'codeveloper', 'region']);}
      ])->latest()->get();
    }

    /**
     * Show the form for creating a new Game.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Game in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate
        $this->validate($request, [
            'title' => 'required|unique:games',
            'synopsis' => 'required|min:10',
            'genres' => 'required',
            'icon' => 'required|image64:jpeg,jpg,png',
            'banner' => 'required|image64:jpeg,jpg,png',
            'developer' => 'required',
            'releases' => 'required'
        ]);

        //Create the game object
        $game = Game::create($request);

        //Eager load the game, with the associated User, Genres, Developer and Releases
        $game->load(['user', 'genres', 'developer', 'releases']);

        return response()->json($game, 201);
    }

    /**
     * Display the specified Game.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::with([
            'user',
            'genres',
            'developer',
            'recommendations' => function($q) {$q->with([
                'secondGame'
            ]);},
            'reviews' => function($q) {$q->with([
                'release' => function($q) {$q->with(['platform']);}, 
                'user'
            ])->orderBy('created_at', 'desc')->limit(2);},
            'releases' => function($q) {$q->with(['platform', 'publisher', 'codeveloper', 'region']);},
            'libraries' => function($q) {$q->with([
                'user', 
                'playStatus',
                'release' => function($q) {$q->with(['platform']);}
            ])->orderBy('updated_at', 'desc')->limit(6);}
        ])->withCount('reviews', 'recommendations')->where('id', '=', $id)->first();

        if($game == null) { 
            return response()->json([
                'error' => 'Game not found'
            ], 404);
        } 
        else { 
            $game->trending_page_views += 1;
            $game->save();
            return $game; 
        }
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
        $this->validate($request,[
            'title' => 'required|unique:games,title,'.$id,
            'synopsis' => 'required|min:10',
            'genres' => 'required',
            'developer' => 'required'
        ]);

        $game = Game::find($id);
            $game->title = $request->title;
            $game->synopsis = $request->synopsis;
            $game->developer_id = $request->developer['id'];
                
            $filteredCollection = collect($request->genres)->pluck(['id'])->all();
            $game->genres()->sync($filteredCollection); //sync: Any IDs that are not in the given array will be removed from the intermediate table
            
            if(!empty($request->icon)){
                //make image and upload
                $icon = Image::make($request->get('icon'));
                $iconExtension = explode('/', $icon->mime() )[1];
                $iconName = $game->id.'.'.$iconExtension;
                $icon = $icon->stream(); 
                Storage::disk('spaces')->put("games/icons/$iconName", (string) $icon, 'public');

                $game->icon = $iconName;
            }
            if(!empty($request->banner)){
                //make image and upload
                $banner = Image::make($request->get('banner'));
                $bannerExtension = explode('/', $banner->mime() )[1];
                $bannerName = $game->id.'.'.$bannerExtension;
                $banner = $banner->stream();
                Storage::disk('spaces')->put("games/banners/$bannerName", (string) $banner, 'public');

                $game->banner = $bannerName;
            }

        $game->save();

        return response()->json($game, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Game::find($id);

        //Remove images from Spaces
        Storage::disk('spaces')->delete([
            "games/banners/$game->banner",
            "games/icons/$game->icon"
        ]);

        $game->delete();

        return response()->json(null, 204);
    }
}
