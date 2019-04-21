<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ForumFilters extends QueryFilters
{
    /**
     * Filter Discussions by recent or popular.
     *
     * @param  string $order
     * @return Builder
     */
    public function sort($order = 'recent')
    {
        if($order == 'recent') 
        {
            return $this->builder->orderBy('created_at', 'desc');
        }
        else if($order == 'popular') 
        {
            return $this->builder->orderBy('post_count', 'desc');
        }
        else if($order == 'likes') 
        {
            return $this->builder->orderBy('like_count', 'desc');
        }
        else if($order == 'views') 
        {
            return $this->builder->orderBy('view_count', 'desc');
        }
        else if($order == 'users') 
        {
            return $this->builder->orderBy('user_count', 'desc');
        }
        else { return $this->builder; }
    }

    /**
     * Filter by search keyword.
     *
     * @param  string $genre
     * @return Builder
     */
    public function search($search = null)
    {
        if(empty($search)) {return $this->builder;}
        return $this->builder->where('title', 'like', ('%'.$search.'%'));
    }

    // /**
    //  * Filter by Tag.
    //  *
    //  * @param  string $slug
    //  * @return Builder
    //  */
    // public function tag($slug = '')
    // {
    //     $slug = trim($slug);
             
    //     //Check if empty
    //     if(isset($slug)) {
    //         // It's empty
    //         return $this->builder;
    //     }
    //     else {
    //         //Ensure format is "tag-name-12"
    //         // $re = '/^\d+(?:,\d+)*$/';
    //         // if(!preg_match($re, $slug)) {return $this->builder;}
        
    //         return $this->builder
    //         ->select('discussion.*')
    //         ->distinct('discussion.id')
    //         ->join('discussion_tag', 'discussion.id', '=', 'discussion_tag.discussion_id')
    //         ->join('tags', 'tags.id', '=', 'discussion_tag.tag_id')
    //         ->where('tags.slug', $slug);
    //     }
    // }

}