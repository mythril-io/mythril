<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cache;

class Game extends Model
{
    use Filters\Filterable;

    // protected $appends = ['score', 'library_count', 'score_rank', 'popularity_rank'];
    /**
     * Hide the pivot table information, and the timestamps
     */
    protected $hidden = array('user_id', 'developer_id');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'synopsis', 'user_id', 'icon', 'banner', 'developer_id', 'score', 'library_count', 'score_rank', 'popularity_rank'
    ];

    /**
     * The User that created the Game.
     */
    public function user()
    {
        return $this->belongsTo('App\User')
            ->select(['id','username']);
    }

    /**
     * The Developer the Game belongs to.
     *
     */
    public function developer()
    {
       return $this->belongsTo('App\Developer')
            ->select(['id','name']);
    }

   /**
    * The Genres that the Game belongs to.
    */
   public function genres()
   {
       return $this->belongsToMany('App\Genre');
   }

   /**
    * The Releases the Game has.
    *
    */
   public function releases() {
     return $this->hasMany('App\Release');
   }

    /**
     * The Library Entries the Game has.
     */
    public function libraries()
    {
        return $this->hasMany('App\Library');
    }

    /**
     * The Reviews the Game has.
     */
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    /**
     * The Recommendations the Game has.
     */
    public function recommendations()
    {
        return $this->hasMany('App\Recommendation');
    }

    /**
     * Scope for adding a row column.
     */
    public function scopeWithRowNumber($query, $columns = ['*'])
    {
        // Set the row number
        $offset = (int) $query->getQuery()->offset;
        DB::statement(DB::raw("set @row={$offset}"));

        // Adjust SELECT clause to contain the row
        if ( ! count($query->getQuery()->columns)) $query->select($columns);
        $sub = $query->addSelect([DB::raw('@row:=@row+1 as row')]);

        // Return the result instead of builder object
        return $query;
    }

    /**
     * Reset trending_pages_views for each game
     */
    public static function resetTrendingViews() 
    {
        // $games = Game::all()
        // foreach($games as $game) {
        //     $game->trending_page_views = 0;
        // }

      Game::chunk(100, function ($games) {
        foreach ($games as $game) {
          $game->update(['trending_page_views' => null]);
        }
      });
    }

    /**
     * Update score, libraryCount, score_rank, popularity_rank for all games
     */
    public static function updateAllRatings() 
    {
      //Update score for all games
      Game::chunk(100, function ($games) {
        foreach ($games as $game) {
          $score = ($game->libraries()->count() > 0 ? number_format(($game->libraries()->avg('score')/10)*100, 2) : null);
          $game->update(['score' => $score]);
        }
      });

      //Update library_count for all games
      Game::chunk(100, function ($games) {
        foreach ($games as $game) {
          $libraryCount = $game->libraries()->count();
          $game->update(['library_count' => $libraryCount]);
        }
      });

      //Update score_rank for all games
      Game::orderBy('score', 'desc')->withRowNumber()->chunk(100, function ($games) {
        foreach ($games as $game) {
          $game->update(['score_rank' => $game->row]);
        }
      });

      //Update popularity_rank for all games
      Game::orderBy('library_count', 'desc')->withRowNumber()->chunk(100, function ($games) {
        foreach ($games as $game) {
          $game->update(['popularity_rank' => $game->row]);
        }
      });
    }

   /**
    * Store a newly created Game in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \App\Game  $game
    */
   public static function create(Request $request)
   {
      //Create Game object
      $game = $request->user()->games()->create([
        'title' => $request->title,
        'synopsis' => $request->synopsis,
        'icon' => "default.png",
        'banner' => "default.png",
        'developer_id' => $request->developer['id']
      ]);

      //Ready Images
      $icon = Image::make($request->get('icon'));
      $banner = Image::make($request->get('banner'));

      //Icon Image
      $iconExtension = explode('/', $icon->mime() )[1];
      $iconName = $game->id.'.'.$iconExtension;
      $icon = $icon->stream(); 
      Storage::disk('spaces')->put("games/icons/$iconName", (string) $icon, 'public');
      //$iconName = Storage::disk('spaces')->url($iconName);

      //Banner Image
      $bannerExtension = explode('/', $banner->mime() )[1];
      $bannerName = $game->id.'.'.$bannerExtension;
      $banner = $banner->stream();
      Storage::disk('spaces')->put("games/banners/$bannerName", (string) $banner, 'public');
      //$bannerName = Storage::disk('spaces')->url($bannerName);
      
      //Update Game Object with images
      $game->icon = $iconName;
      $game->banner = $bannerName;
      $game->save();

      //Attach genres
      $filteredCollection = collect($request->genres)->pluck(['id'])->all();
      $game->genres()->sync($filteredCollection); //sync: Any IDs that are not in the given array will be removed from the intermediate table

      //Attach release(s)
      $game->addReleases($request->releases, $game->id);

      return $game;
   }

   /**
    * Add Releases for the Game
    *
    * @param  Array $releases
    * @return void
    */
    
   public function addReleases(Array $releases, $gameid)
   {
     foreach($releases as $release)
     {
       $releaseMatch = DB::table('releases')->where([
          ['alternate_title', array_get($release, 'altTitle')],
          ['platform_id', array_get($release, 'platform.id')],
          ['publisher_id', array_get($release, 'publisher.id')],
          ['game_id', $gameid],
      ])->count();
       
       if($releaseMatch == 0) 
       {
           $this->releases()->create([
             'platform_id' => array_get($release, 'platform.id'),
             'publisher_id' => array_get($release, 'publisher.id'),
             'codeveloper_id' => array_get($release, 'codeveloper.id', null),
             'alternate_title' => array_get($release, 'altTitle'),
             'NA' => array_get($release, 'NA'),
             'EU' => array_get($release, 'EU'),
             'JP' => array_get($release, 'JP'),
             'WW' => array_get($release, 'WW')
           ]);
       } 
     }

   }
}
