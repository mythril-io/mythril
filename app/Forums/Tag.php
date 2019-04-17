<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
        'name' , 'colour', 'order', 'parent_id'
    ];

    /**
     * The Discussions for the Tag.
     */
    public function discussions()
    {
        return $this->hasMany('App\Forum\Discussion');
    }
}
