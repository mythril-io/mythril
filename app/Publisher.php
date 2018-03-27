<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
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
        'name' , 'country', 'description'
    ];

    /**
     * The Releases for the Publisher.
     */
    public function releases()
    {
        return $this->hasMany('App\Release');
    }
}
