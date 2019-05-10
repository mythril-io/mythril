<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use App\User;

class Post extends Model
{
    use CanBeLiked;

    protected $appends = ['has_liked'];

    /**
     * Hide the pivot table information, and the timestamps
     */
    protected $hidden = array('user_id');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body', 'discussion_id', 'user_id', 'edit_count', 'edit_time'
    ];

    /**
     * The User the Post belongs to.
     *
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }


    /**
     * The Discussion the Post belongs to.
     *
     */
    public function discussion()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get User Like Status
     *
     * @return boolean
     */
    public function getHasLikedAttribute()
    {
        $user = User::get();
        return $this->isLikedBy($user);
    }

}
