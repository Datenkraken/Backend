<?php

namespace App\Filter\Contracts\Traits;

use DateTime;

/*
 * Implements a basic start- and endtime setter. To be used with the TimeFilterInterface.
 *
 * @see TimeFilterInterface
 */
trait TimeTrait
{
    private $startTime;
    private $endTime;

    /*
     * @see TimetFilterInterface
     */
    public function startTime($time)
    {
        if ($time instanceof DateTime) {
            $this->startTime = $time;
        } else {
            $this->startTime = new DateTime($time);
        }

        return $this;
    }

    /*
     * @see TimetFilterInterface
     */
    public function endTime($time)
    {
        if ($time instanceof DateTime) {
            $this->endTime = $time;
        } else {
            $this->endTime = new DateTime($time);
        }
        return $this;
    }
}
