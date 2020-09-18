<?php

namespace App\Models\ProcessedData;

use App\Models\User;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsToMany;

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
        'start_time',
        'end_time'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'timestamp',
        'end_time' => 'timestamp'
    ];

    public function participants() {
        return $this->belongsToMany(User::class, null, 'encounters', 'participants');
    }
}
