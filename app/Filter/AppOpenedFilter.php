<?php

namespace App\Filter;

use App\Filter\Contracts\Interfaces\FilterInterface;
use App\Filter\Contracts\Interfaces\TimeFilterInterface;
use App\Filter\Contracts\Traits\TimeTrait;
use App\Models\Datamining\AppEvent;
use Carbon\CarbonPeriod;
use DateInterval;

/*
 * This filter calculates the added users in a given timeframe and returns the number of added
 * users per day.
 */
class AppOpenedFilter implements FilterInterface, TimeFilterInterface
{
    use TimeTrait;

    /**
     * @var AppEvent[]
     */
    private $events;

    /*
     * Set the app events.
     *
     * @param array The events to be used with this filter.
     *
     * @return Filter Returns the associated Filter.
     */
    public function appEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    /*
     * @see FilterInterface
     */
    public function filter()
    {
        if ($this->events === null) {
            throw new MissingFilterArgumentException('Missing events argument');
        }

        $period = collect(new CarbonPeriod(
            $this->startTime,
            new DateInterval('P1D'),
            $this->endTime
        ))->map(function ($item) {
            return $item->format('Y-m-d');
        })->mapWithKeys(function ($item) {
            return [$item => 0];
        });

        $openevents = $this->events->where('type', '=', 'started')
            ->whereBetween('timestamp', [$this->startTime, $this->endTime])
            ->countBy(function ($time) {
                return $time->timestamp->format('Y-m-d');
            });

        return $openevents->union($period)->sortKeys();
    }
}
