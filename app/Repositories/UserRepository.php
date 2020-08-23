<?php

namespace App\Repositories;

use App\Models\DeletedUserRecord;
use App\Models\User;
use App\Events\AccountDeleted;
use Carbon\Carbon;

class UserRepository extends EloquentRepository
{
    /**
     * Defines the associated model.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Delete entry with id
     *
     * @param mixed $id id of the entry
     *
     * @return bool true if deletion was successful
     */
    public function delete($id): bool
    {
        // this also needs to delete all data associated with the user
        $instance = $this->getBuilder()->findOrFail($id);

        // Create a new record for the deleted user
        $deleteRecord = new DeletedUserRecord();
        $deleteRecord->timestamp = Carbon::now();
        $deleteRecord->account_created_at = $instance->created_at;
        $deleteRecord->save();

        // Delete all user specific data
        $instance->dataWifi()->delete();
        $instance->dataAppEvents()->delete();
        $instance->dataArticleEvents()->delete();
        $instance->dataSourceEvents()->delete();
        $instance->dataCoords()->delete();
        $instance->dataBluetoothScans()->delete();
        $instance->dataBondBluetoothDevices()->delete();
        $instance->dataOSInformation()->delete();
        $instance->dataUserActivity()->delete();
        $instance->dataPermissionStates()->delete();

        // Delete the actual user model
        $result = $instance->delete();

        // if the user was actually deleted, then emit the deletion event.
        if ($result === true) {
            event(new AccountDeleted($instance));
        }

        return $result;
    }
}
