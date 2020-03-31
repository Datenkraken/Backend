<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Model class for retention policies. Note that only one instance of this model
 * should be used.
 */
class RetentionPolicy extends Model
{
    protected $dates = ['globalRetentionDate'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enableGlobalRetention',
        'globalRetentionDate',
        'enableDefaultRetention',
        'defaultRetentionDays',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'enableGlobalRetention' => false,
        'enableDefaultRetention' => false,
        'globalRetentionExecuted' => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enableGlobalRetention' => 'boolean',
        'enableDefaultRetention' => 'boolean',
        'globalRetentionExecuted' => 'boolean',
    ];
}
