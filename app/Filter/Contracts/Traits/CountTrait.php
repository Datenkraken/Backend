<?php

namespace App\Filter\Contracts\Traits;

/*
 * Implements a basic count setter. To be used with the CountFilterInterface.
 */
trait CountTrait
{
    private $count;

    /*
     * @see CountFilterInterface
     */
    public function count($count)
    {
        $this->count = $count;
        return $this;
    }
}
