<?php

namespace App\Providers;
   use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Google\Cloud\Translate\V2\TranslateClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


public function boot()
{
    $locale = session('locale', config('app.locale')); // Default to config locale if none in session
    App::setLocale($locale);
    
    \Log::info('Current locale: ' . session('locale') . ' | App locale: ' . app()->getLocale());
    
    Paginator::useBootstrapFive();

}

    
}

class GoogleTranslateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('google.translate', function () {
            return new TranslateClient([
                'key' => env('GOOGLE_TRANSLATE_API_KEY'),
            ]);
        });
    }
}