<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Model class for imprints. Note that only one instance of this model
 * should be used.
 */
class Imprint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
    ];
}
