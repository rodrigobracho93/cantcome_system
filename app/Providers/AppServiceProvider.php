<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $systemName = Setting::get('system_name', 'CantCome');
            $systemLogo = Setting::get('system_logo', 'logo.png');
            $view->with('systemName', $systemName);
            $view->with('systemLogo', $systemLogo);
        });
    }
}
