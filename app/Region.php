<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
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
        'name' , 'acronym'
    ];

    /**
     * The Releases for the Region.
     */
    public function releases()
    {
        return $this->hasMany('App\Release');
    }
}
