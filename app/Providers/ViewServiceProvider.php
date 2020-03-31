<?php

namespace App\Providers;

use ExportLocalization;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using Closure based composers...
        View::composer('layouts.app', function ($view) {
            $origin = Request::input('origin');

            $view->with([
                'messages' => ExportLocalization::export()->toFlat(),
                'origin' => $origin,
            ]);
        });
    }
}
