<?php

namespace App\Models\Datamining;

/**
 * Model class for article events. The type attribute can have
 * the following values: 'opened', 'chromeopen', 'saved', 'shared'.
 */
class ArticleEvent extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'type',
        'title',
        'url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
