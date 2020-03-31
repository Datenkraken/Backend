<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Model class for source categories.
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    /**
     * The RssSources that belong to this category.
     */
    public function rssSources()
    {
        return $this->belongsToMany(
            'App\Models\RssSource',
            null,
            'category_ids',
            'rss_source_ids'
        );
    }
}
