<?php

namespace App\Models\Datamining;

/**
 * Model class for coordinates about the users location
 */
class BluetoothBondDevice extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'name',
        'address'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime'
    ];
}
