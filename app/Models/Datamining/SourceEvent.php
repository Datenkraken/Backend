<?php

namespace App\Models\Datamining;

/**
 * Model class for source events. The type attribute can have
 * the following values: 'added', 'removed', 'filtered'.
 */
class SourceEvent extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'type',
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
