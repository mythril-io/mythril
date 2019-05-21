<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelFollow\Traits\CanSubscribe;
use Overtrue\LaravelFollow\Traits\CanLike;
use Overtrue\LaravelFollow\Traits\CanVote;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, CanSubscribe, CanLike, CanVote;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'is_verified', 'about_me', 'timezone', 'birthday', 'gender', 'location', 'avatar', 'banner', 'twitter', 'facebook', 'instagram', 'discord', 'deviant_art', 'reddit', 'patreon', 'tumblr', 'youtube', 'twitch', 'battle_net', 'steam', 'playstation', 'nintendo_switch', 'xbox'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'updated_at', 'is_verified'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Return boolean, for whether User has role.
     *
     * @return Boolean
     */
    public function checkRole($role)
    {
        return $this->hasRole($role);
    }

    /**
     * The Games created by this User.
     */
    public function games()
    {
        return $this->hasMany('App\Game');
    }

    /**
     * The Games owned by this User.
     */
    public function libraries()
    {
        return $this->hasMany('App\Library');
    }

    /**
     * The Reviews created by this User.
     */
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    /**
     * The Recommendations created by this User.
     */
    public function recommendations()
    {
        return $this->hasMany('App\Recommendation');
    }

    /**
     * The Favourites for this User.
     */
    public function favourites()
    {
        return $this->hasMany('App\Favourite');
    }

    /**
     * The Followers for this User.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'leader_id', 'follower_id')->select('follower_id', 'username', 'avatar');
    }

    /**
     * The Followings for this User.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'leader_id')->select('leader_id', 'username', 'avatar'); 
    }

    /**
     * Return User ID if authenticated
     * 
     * @return mixed
     */
    public static function getID() 
    {
        return isset(Auth::guard()->user()->id) ? Auth::guard()->user()->id : null;
    }

    /**
     * Return User if authenticated
     * 
     * @return mixed
     */
    public static function get() 
    {
        return Auth::guard()->user();
    }

    /**
     * The permissions for this User.
     */
    public function permissions()
    {
        return $this->permissions;
    }
}
