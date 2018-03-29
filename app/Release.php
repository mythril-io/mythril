<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    /**
     * Hide the some table columns, and the timestamps
     */
    protected $hidden = array(
      'created_at',
      'updated_at',
      'platform_id',
      'publisher_id',
      'codeveloper_id',
      'game_id'
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'platform_id', 'publisher_id', 'codeveloper_id', 'alternate_title',
        'NA', 'EU', 'JP', 'WW'
    ];

    /**
     * The Game the Release belongs to.
     *
     */
    public function game()
    {
      return $this->belongsTo('App\Game', 'game_id');
    }

    /**
     * The Platform the Release belongs to.
     *
     */
    public function platform()
    {
      return $this->belongsTo('App\Platform', 'platform_id');
    }

    /**
     * The Co-Developer the Release belongs to.
     *
     */
    public function codeveloper()
    {
       return $this->belongsTo('App\Developer', 'codeveloper_id')
            ->select(['id','name']);
    }

    /**
     * The Publisher the Release belongs to.
     *
     */
    public function publisher()
    {
      return $this->belongsTo('App\Publisher', 'publisher_id')
            ->select(['id','name']);
    }

    /**
     * The Library Entries the Release has.
     */
    public function libraries()
    {
        return $this->hasMany('App\Library');
    }

    /**
     * The Reviews the Release has.
     */
    public function reviews()
    {
        return $this->hasMany('App\Review');
    }
}
