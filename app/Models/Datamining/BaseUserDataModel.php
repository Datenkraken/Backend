<?php

namespace App\Models\Datamining;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Base class for all datamining models. This class
 * adds the user relation to the model.
 */
abstract class BaseUserDataModel extends Model
{
    /**
     * The user that this data belongs to.
     *
     * @return BelongsTo the relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
