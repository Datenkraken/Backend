<?php

namespace App\Providers;

use App\Rules\RedirectRule;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class PassportCustomUriSchemeProvider extends ServiceProvider
{
    public function register()
    {
        /*
         * Passport client extends Eloquent model by default, so we alias them.
         */
        $loader = AliasLoader::getInstance();
        $loader->alias('Laravel\Passport\Http\Rules\RedirectRule', RedirectRule::class);
    }
}
