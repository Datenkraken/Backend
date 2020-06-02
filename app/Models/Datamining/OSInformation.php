<?php

namespace App\Models\Datamining;

/**
 * Model class for OS Information
 */
class OSInformation extends BaseUserDataModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timestamp',
        'sdk',
        'device',
        'model',
        'vendor',
        'serial'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'sdk' => 'int',
    ];
}
