<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RedirectRule implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function passes($attribute, $value)
    {
        foreach (explode(',', $value) as $redirect) {
            if (! filter_var($redirect, FILTER_VALIDATE_URL)) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function message()
    {
        return 'One or more redirects have an invalid url format.';
    }
}
