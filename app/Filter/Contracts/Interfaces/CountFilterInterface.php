<?php

namespace App\Filter\Contracts\Interfaces;

/*
 * Interface for every filter that wants to implement a count method.
 */
interface CountFilterInterface
{
    /*
     * Set the count of results to be returned.
     *
     * @param int The number of results to be returned
     * @return Filter Returns the associated Filter.
     */
    public function count($count);
}
