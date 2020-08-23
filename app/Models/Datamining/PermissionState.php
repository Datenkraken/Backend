<?php

namespace App\Models\Datamining;

/**
 * Model class for app events. The type attribute can have
 * the following values: 'closed', 'scroll', 'background', 'started',
 * 'closed'.
 */
class PermissionState extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'permission',
        'state'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'state' => 'boolean'
    ];
}
