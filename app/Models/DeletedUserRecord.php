<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Model class for deleted user records.
 */
class DeletedUserRecord extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Names of fields that are dates.
     *
     * @var array
     */
    protected $dates = [
        'timestamp',
        'account_created_at',
    ];

    /**
     * Name of the database collection for the model.
     *
     * @var string
     */
    protected $collection = 'deleted_user_record';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'account_created_at',
    ];
}
