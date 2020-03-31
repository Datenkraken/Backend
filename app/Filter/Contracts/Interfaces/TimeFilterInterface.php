<?php

namespace App\Filter\Contracts\Interfaces;

/*
 * Interface for every filter that wants to implement a timeframe for results.
 */
interface TimeFilterInterface
{
    /*
     * Set the start time of the returned results.
     *
     * @param mixed A value that can be parsed to a DateTime or a DateTime object.
     * This sets the start time of the timeframe.
     *
     * @return Filter Returns the associated filter.
     */
    public function startTime($time);

    /*
     * Set the end time of the returned results.
     *
     * @param mixed A value that can be parsed to a DateTime or a DateTime object.
     * This sets the end time of the timeframe.
     *
     * @return Filter Returns the associated filter.
     */
    public function endTime($time);
}
