<?php

namespace App\Filter;

use App\Filter\Contracts\Interfaces\FilterInterface;
use App\Filter\Contracts\Interfaces\TimeFilterInterface;
use App\Filter\Contracts\Traits\TimeTrait;
use Carbon\CarbonPeriod;
use DateInterval;
use Illuminate\Database\Eloquent\Collection;

/*
 * This filter calculates the current amount of users per day and returns all added
 * users, all deleted users and the overall users.
 */
class AllUsersIntervalFilter implements FilterInterface, TimeFilterInterface
{
    use TimeTrait;

    /**
     * The deleted users collection.
     *
     * @var Collection
     */
    protected $deletedUsers;

    /**
     * The users collection.
     *
     * @var Collection
     */
    protected $users;

    /*
     * Set the deleted users.
     *
     * @param Collection The deleted users to be used with this filter.
     * @return Filter Returns the associated Filter.
     */
    public function deletedUsers(Collection $users)
    {
        $this->deletedUsers = $users;
        return $this;
    }

    /*
     * Set the users.
     *
     * @param Collection The users to be used with this filter.
     * @return Filter Returns the associated Filter.
     */
    public function users(Collection $users)
    {
        $this->users = $users;
        return $this;
    }

    /*
     * @see FilterInterface
     */
    public function filter()
    {
        if ($this->deletedUsers === null) {
            throw new MissingFilterArgumentException('Missing deletedUsers argument');
        }

        if ($this->users === null) {
            throw new MissingFilterArgumentException('Missing users argument');
        }

        // create time period
        $period = collect(CarbonPeriod::create(
            $this->startTime,
            new DateInterval('P1D'),
            $this->endTime
        ))->map(function ($item) {
            return $item->format('Y-m-d');
        })->mapWithKeys(function ($item) {
            return [$item => 0];
        });

        $added = clone $period;
        $deleted = clone $period;
        $sumvalue = 0;

        // Gather information from each deletion record and fill the added / deleted collections.
        $this->deletedUsers->each(function ($record) use (&$sumvalue, &$added, &$deleted) {

            // Gather information from the deletion timestamp
            if ($record->timestamp->isBefore($this->startTime)) {
                // Account was deleted before the timespan
                $sumvalue -= 1;
            } elseif (! $record->timestamp->isAfter($this->endTime)) {
                // Account was deleted within the timespan,
                // so add it to the specific date in deleted.
                $day = $record->timestamp->format('Y-m-d');
                $deleted[$day] += 1;
            }

            // Gather information from the account creation date
            if ($record->account_created_at->isBefore($this->startTime)) {
                // Account has been created befor the timespan
                $sumvalue += 1;
            } elseif (! $record->account_created_at->isAfter($this->endTime)) {
                // Account creation is in the timespan, so add it to the specific date in added.
                $day = $record->account_created_at->format('Y-m-d');
                $added[$day] += 1;
            }
        });

        // Gather information from all still existing user instances and
        // add them to the existing added collection.
        $this->users->each(function ($user) use (&$sumvalue, &$added) {
            if ($user->created_at->isBefore($this->startTime)) {
                // Account was created before the time span, so add to sum
                $sumvalue += 1;
            } elseif (! $user->created_at->isAfter($this->endTime)) {
                // Account exists and is created within the timespan so
                // add it to the specific date in added.
                $day = $user->created_at->format('Y-m-d');
                $added[$day] += 1;
            }
        });

        // Calculate the sum for each day based on the sumvalue and
        // the added / deleted collections
        $sum = $period->map(function ($key, $date) use (&$sumvalue, $added, $deleted) {
            $sumvalue = $sumvalue + $added[$date] - $deleted[$date];
            return $sumvalue;
        });

        return [
            'added' => $added,
            'deleted' => $deleted,
            'all' => $sum,
        ];
    }
}
