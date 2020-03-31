<?php

namespace App\Filter\Contracts\Interfaces;

/*
 * Basic Interface for every filter. Forces to implement the filter method.
 */
interface FilterInterface
{
    /*
     * Executes the filter and returns the results.
     *
     * @return mixed The Result of the filter.
     */
    public function filter();
}
