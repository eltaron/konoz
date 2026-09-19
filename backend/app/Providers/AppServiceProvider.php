<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        View::composer('includes.public.footer', function (\Illuminate\View\View $view) {
            $view->with('socialLinks', Setting::whereIn('key', [
                'social_facebook',
                'social_twitter',
                'social_instagram',
                'social_whatsapp',
                'social_telegram',
            ])->get()->pluck('value', 'key'));
        });
    }
}
