<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if( request()->ip() === '218.235.94.223' ){
            config(['app.env' => 'local']);
            config(['app.debug' => true]);
            config(['debugbar.enabled' => true]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
