<?php

namespace App\Models\Datamining;

/**
 * Model class for coordinates about the users location
 */
class BluetoothDeviceScan extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'name',
        'address',
        'known'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'known' => 'boolean'
    ];
}
