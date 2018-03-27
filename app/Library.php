<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Library extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Hide the pivot table information, and the timestamps
     */
    protected $hidden = array('updated_at', 'deleted_at');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'game_id', 'release_id', 'play_status_id', 'score', 'own', 'digital', 'hours', 'notes'
    ];

    /**
     * The Game the Library entry belongs to.
     *
     */
    public function game()
    {
      return $this->belongsTo('App\Game', 'game_id');
    }

    /**
     * The User the Library entry belongs to.
     *
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The Release the Library entry belongs to.
     *
     */
    public function release()
    {
      return $this->belongsTo('App\Release', 'release_id');
    }

    /**
     * The Play Status the Library entry belongs to.
     *
     */
    public function playStatus()
    {
      return $this->belongsTo('App\PlayStatus', 'play_status_id');
    }
}
