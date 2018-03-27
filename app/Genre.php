<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    /**
     * Hide the pivot table information, and the timestamps
     */
    protected $hidden = array('pivot', 'created_at', 'updated_at');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'acronym'
    ];

    /**
     * The Games that the Genre belongs to.
     */
    public function games()
    {
        return $this->belongsToMany('App\Game');
    }
}
