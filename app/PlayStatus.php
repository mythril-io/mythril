<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayStatus extends Model
{
    /**
     * Hide the pivot table information, and the timestamps
     */
    protected $hidden = array('created_at', 'updated_at');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The Library Entries associated with this Play Status.
     */
    public function libraries()
    {
        return $this->hasMany('App\Library');
    }
}
