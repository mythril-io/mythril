<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class GameFilters extends QueryFilters
{
    /**
     * Filter by score.
     *
     * @param  string $order
     * @return Builder
     */
    public function score($score = 0)
    {
        if($score > 0 && $score <= 100) 
        {
            return $this->builder->where('score', '>=', $score);
        }
        if($score = 0) 
        {
            return $this->builder;
        }
        else { return $this->builder; }
    }

    /**
     * Filter by popularity, rating or recently added.
     *
     * @param  string $order
     * @return Builder
     */
    public function sort($order = 'popular')
    {
        if($order == 'popular') 
        {
            return $this->builder->orderByRaw('-popularity_rank desc');
        }
        else if($order == 'recent') 
        {
            return $this->builder->orderBy('created_at', 'desc');
        }
        else if($order == 'rating') 
        {
            return $this->builder->orderByRaw('-score_rank desc');
        }
        else { return $this->builder; }
    }

    /**
     * Filter by developer.
     *
     * @param  string $id
     * @return Builder
     */
    public function developer($id = '')
    {
        if(empty($id)) {return $this->builder;}
        return $this->builder->where('developer_id', $id);

        // $developerName = str_replace("-"," ",$level)
        // return $this->builder->join('developers', 'developer_id', '=', 'developers.id')->where('developers.name', $level);
    }

    /**
     * Filter by Publisher.
     *
     * @param  string $id
     * @return Builder
     */
    public function publisher($id = '')
    {
        //Check if empty
        if(empty($id)) {return $this->builder;}

        //Ensure format is "n"

        return $this->builder
            ->select('games.id', 
                'games.title', 
                'games.synopsis', 
                'games.icon', 
                'games.developer_id', 
                'games.user_id', 
                'games.created_at', 
                'games.score', 
                'games.popularity_rank', 
                'games.score_rank')
            ->distinct('games.id')
            ->join('releases', 'games.id', '=', 'releases.game_id')
            ->where('releases.publisher_id', $id);
    }

    /**
     * Filter by Platform.
     *
     * @param  string $id
     * @return Builder
     */
    public function platforms($ids = '')
    {
        $ids = str_replace(["[", "]"], ["", ""], $ids);

        //Check if empty
        if(empty($ids)) {return $this->builder;}

        //Ensure format is "n,n,n"
        $re = '/^\d+(?:,\d+)*$/';
        if(!preg_match($re, $ids)) {return $this->builder;}

        //Make an Array
        $ids = explode(',', $ids);

        return $this->builder
            ->select('games.id', 
                'games.title', 
                'games.synopsis', 
                'games.icon', 
                'games.developer_id', 
                'games.user_id', 
                'games.created_at', 
                'games.score', 
                'games.popularity_rank', 
                'games.score_rank')
            ->distinct('games.id')
            ->join('releases AS rTable', 'games.id', '=', 'rTable.game_id')
            ->whereIn('rTable.platform_id', $ids);
    }

    /**
     * Filter by Genre.
     *
     * @param  string $id
     * @return Builder
     */
    public function genres($ids = '')
    {
        $ids = str_replace(["[", "]"], ["", ""], $ids);

        //Check if empty
        if(empty($ids)) {return $this->builder;}

        //Ensure format is "n,n,n"
        $re = '/^\d+(?:,\d+)*$/';
        if(!preg_match($re, $ids)) {return $this->builder;}

        //Make an Array
        $ids = explode(',', $ids);

        return $this->builder
            ->select('games.id', 
                'games.title', 
                'games.synopsis', 
                'games.icon', 
                'games.developer_id', 
                'games.user_id', 
                'games.created_at', 
                'games.score', 
                'games.popularity_rank', 
                'games.score_rank')
            ->distinct('games.id')
            ->join('game_genre', 'games.id', '=', 'game_genre.game_id')
            ->whereIn('game_genre.genre_id', $ids);
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
}