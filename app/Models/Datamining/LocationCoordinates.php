<?php

namespace App\Models\Datamining;

/**
 * Model class for coordinates about the users location
 */
class LocationCoordinates extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'altitude',
        'longitude',
        'latitude',
        'accuracy',
        'provider'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'altitude' => 'double',
        'longitude' => 'double',
        'latitude' => 'double',
        'accuracy' => 'float'
    ];
}
