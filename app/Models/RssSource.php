<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Model class for rss source.
 */
class RssSource extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'url',
    ];

    /**
     * The categories that belong to this RssSource.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }
}
