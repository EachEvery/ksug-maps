<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TANIOS\Airtable\Airtable;

class AirtableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->app->bind(Airtable::class, function () {
            return new Airtable([
                'api_key' => config('services.airtable.key'),
                'base' => config('services.airtable.base'),
            ]);
        });
    }
}
