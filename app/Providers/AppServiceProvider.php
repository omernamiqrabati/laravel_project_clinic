<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
// app/Providers/AppServiceProvider.php

public function register()
{
    $this->app->singleton(\App\Services\SupabaseService::class, function ($app) {
        return new \App\Services\SupabaseService(
            config('services.supabase.url'),
            config('services.supabase.key'),
            $app->make(\Psr\Log\LoggerInterface::class)
        );
    });
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}