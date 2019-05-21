<?php

namespace App;

use Cog\Laravel\Love\Likeable\Models\Traits\Likeable;
use Overtrue\LaravelFollow\Traits\CanBeVoted;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use CanBeVoted;

    protected $appends = ['like_count', 'dislike_count'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id', 'release_id', 'summary', 'content', 'score'
    ];

    /**
     * The User the Review belongs to.
     *
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The Game the Review belongs to.
     *
     */
    public function game()
    {
      return $this->belongsTo('App\Game', 'game_id');
    }

    /**
     * The Release the Review belongs to.
     *
     */
    public function release()
    {
      return $this->belongsTo('App\Release', 'release_id');
    }

    /**
     * The number of Likes the Review has
     *
     * @return Int
     */
    public function getLikeCountAttribute()
    {
        return $this->upvoters->count();
    }

    /**
     * The number of Dislikes the Review has
     *
     * @return Int
     */
    public function getDislikeCountAttribute()
    {
        return $this->downvoters->count();
    }

}
