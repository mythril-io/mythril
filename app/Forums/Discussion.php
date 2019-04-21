<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use \App\Filters\Filterable;
    
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
}
