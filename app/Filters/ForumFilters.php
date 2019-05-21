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
            return $this->builder->orderBy('last_posted_at', 'desc');
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
     * @param  string $search
     * @return Builder
     */
    public function search($search = null)
    {
        if(empty($search)) {return $this->builder;}
        return $this->builder->where('title', 'like', ('%'.$search.'%'));
    }

    // /**
    //  * Filter by subscriptions.
    //  *
    //  * @param  string $show
    //  * @return Builder
    //  */
    // public function subscribed($show)
    // {
    //     // if(empty($show)) {return $this->builder;}
    //     // $show = ($show === 'true');
    //     // $show === 'true'? true: false;
    //     if($show === 'true' || $show === true) {
    //         return $this->builder->where('is_subscribed', '=', true);
    //     }
    //     else {
    //         return $this->builder;
    //     }
    // }

}