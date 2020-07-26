<?php

namespace App\Models\ProcessedData;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Model class for app events. The type attribute can have
 * the following values: 'closed', 'scroll', 'background', 'started',
 * 'closed'.
 */
class UserEncounter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'user_id1',
        'user_id2',
        'time_diff'
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
