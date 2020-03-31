<?php

namespace App\Models\Datamining;

/**
 * Model class for wifi data.
 */
class WifiData extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'ssid',
        'bssid',
        'rssi',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'rssi' => 'int',
    ];
}
