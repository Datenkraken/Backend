<?php

namespace App\Models;

use App\Models\Datamining\AppEvent;
use App\Models\Datamining\ArticleEvent;
use App\Models\Datamining\BluetoothBondDevice;
use App\Models\Datamining\BluetoothDeviceScan;
use App\Models\Datamining\LocationCoordinates;
use App\Models\Datamining\OSInformation;
use App\Models\Datamining\PermissionState;
use App\Models\Datamining\SourceEvent;
use App\Models\Datamining\UserActivity;
use App\Models\Datamining\WifiData;
use App\Models\ProcessedData\UserEncounter;
use DesignMyNight\Mongodb\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * Model class for users.
 */
class User extends Authenticatable
{
    use Notifiable;
    use MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_admin' => false,
    ];

    /**
     * The validation rules
     */
    public static function validationRules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * The WifiData associated with this user.
     *
     * @return HasMany the relation.
     */
    public function dataWifi()
    {
        return $this->hasMany(WifiData::class);
    }

    public function dataAppEvents()
    {
        return $this->hasMany(AppEvent::class);
    }

    public function dataArticleEvents()
    {
        return $this->hasMany(ArticleEvent::class);
    }

    public function dataSourceEvents()
    {
        return $this->hasMany(SourceEvent::class);
    }

    public function dataCoords()
    {
        return $this->hasMany(LocationCoordinates::class);
    }

    public function dataBluetoothScans()
    {
        return $this->hasMany(BluetoothDeviceScan::class);
    }

    public function dataBondBluetoothDevices()
    {
        return $this->hasMany(BluetoothBondDevice::class);
    }

    public function dataOSInformation()
    {
        return $this->hasMany(OSInformation::class);
    }

    public function dataUserActivity()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function dataPermissionStates()
    {
        return $this->hasMany(PermissionState::class);
    }

    public function logEntries()
    {
        return $this->hasMany(LogEntry::class);
    }

    public function dataEncounters() {
        return $this->belongsToMany(UserEncounter::class, null, 'participants', 'encounters');
    }
}
