<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id', 'release_id', 'second_game_id', 'second_release_id', 'content'
    ];

    /**
     * The User the Recommendation belongs to.
     *
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The Game the Recommendation belongs to.
     *
     */
    public function game()
    {
      return $this->belongsTo('App\Game', 'game_id');
    }

    /**
     * The Release the Recommendation belongs to.
     *
     */
    public function release()
    {
      return $this->belongsTo('App\Release', 'release_id');
    }

    /**
     * The Second Game the Recommendation belongs to.
     *
     */
    public function secondGame()
    {
      return $this->belongsTo('App\Game', 'second_game_id');
    }

    /**
     * The Second Release the Recommendation belongs to.
     *
     */
    public function secondRelease()
    {
      return $this->belongsTo('App\Release', 'second_release_id');
    }
}
