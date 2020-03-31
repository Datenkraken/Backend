<?php

namespace App\Repositories;

use App\Models\RetentionPolicy;

class RetentionPolicyRepository extends EloquentRepository
{
    /**
     * Defines the associated model.
     *
     * @return string
     */
    public function model()
    {
        return RetentionPolicy::class;
    }

    /**
     * Returns wether or not the global retention policy can currently be scheduled.
     *
     * @return bool
     */
    public function canGlobalRetentionBeScheduled(): bool
    {
        $policy = RetentionPolicy::first();

        if ($policy === null
            || $policy->enableGlobalRetention === false
            || $policy->globalRetentionDate === null) {
            return false;
        }

        return $policy->globalRetentionDate->isPast() && ! $policy->globalRetentionExecuted;
    }

    /**
     * Returns wether or not the default retention policy can currently be scheduled.
     *
     * @return bool
     */
    public function canDefaultRetentionBeScheduled(): bool
    {
        $policy = RetentionPolicy::first();

        return $policy !== null && $policy->enableDefaultRetention === true;
    }

    /**
     * Checks if the current policy settings block a registration.
     *
     * @return bool
     */
    public function isRegistrationBlockedByPolicy(): bool
    {
        $retentionPolicy = RetentionPolicy::first();

        return $retentionPolicy !== null
                && $retentionPolicy->enableGlobalRetention
                && $retentionPolicy->globalRetentionExecuted;
    }
}
