<?php

namespace App\Filter;

use Exception;

/*
 * Is raised when a required argument to a filter is missing.
 */
class MissingFilterArgumentException extends Exception
{
    /**
     * To string function.
     *
     * @return string
     */
    public function __toString()
    {
        return "{$this->message}\n";
    }
}
