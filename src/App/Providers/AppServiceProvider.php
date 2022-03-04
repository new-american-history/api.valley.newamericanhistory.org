<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public static $queryCount = 0;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Analyze all database queries.
        /* DB::listen(function($query) {
            static::$queryCount++;
            echo '===> ' . static::$queryCount . "\n";

            // echo print_r($query->sql, true) . "\n";
            // echo print_r($query->bindings, true) . "\n";
            // echo print_r($query->time, true) . "\n\n\n";
        }); */
    }
}
