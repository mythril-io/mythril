<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
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
     * The Releases for the Developer.
     */
    public function releases()
    {
        return $this->hasMany('App\Release');
    }
}
