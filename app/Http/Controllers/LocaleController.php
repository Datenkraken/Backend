<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Redirector;

/**
 * This controller is responsible for setting the language cookie
 */
class LocaleController extends Controller
{
    /**
     * Sets the language cookie depending on the given lang.
     *
     * @param lang string The new language to use.
     *
     * @return Redirector
     */
    public function __invoke($lang)
    {
        if (in_array($lang, ['de', 'en'])) {
            return redirect()->back()->cookie('locale', $lang, 69420133742);
        }
        abort(405, 'Not supported locale.');
    }
}
