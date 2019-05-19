<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;
use \App\Filters\Filterable;
use Overtrue\LaravelFollow\Traits\CanBeSubscribed;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use App\User;
use App\Forum\Post;

class Discussion extends Model
{
    use Filterable, CanBeSubscribed, CanBeLiked;

    protected $appends = ['is_subscribed', 'has_liked'];

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
        'title' , 'body', 'user_id', 'views', 'edit_count', 'slug'
    ];

    /**
     * The Posts for the Discussion.
     */
    public function posts()
    {
        return $this->hasMany('App\Forums\Post');
    }

   /**
    * The Tags that the Discussion belongs to.
    */
    public function tags()
    {
        return $this->belongsToMany('App\Forums\Tag');
    }

   /**
    * The games that the Discussion belongs to.
    */
    public function games()
    {
        return $this->belongsToMany('App\Game');
    }

    /**
     * The User the Discussion belongs to.
     *
     */
    public function user()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * The Last Post the Discussion belongs to.
     *
     */
    public function lastPost()
    {
      return $this->hasOne('App\Forums\Post', 'id', 'last_post_id');
    }

    /**
     * Get User Subscription Status
     *
     * @return boolean
     */
    public function getIsSubscribedAttribute()
    {
        $user = User::get();
        return $this->isSubscribedBy($user);
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

    /**
     * Get Like Count
     *
     * @return boolean
     */
    public function getLikeCountAttribute()
    {
        return $this->likers->count();
    }

    /**
     * Get Replies Count
     *
     * @return boolean
     */
    public function getPostCountAttribute()
    {
        return $this->posts->count();
    }

    /**
     * Get Unique User Count
     *
     * @return boolean
     */
    public function getUserCountAttribute()
    {
        return $this->posts->groupBy('user_id')->count();
    }
}
