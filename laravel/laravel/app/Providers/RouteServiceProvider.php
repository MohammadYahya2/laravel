<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * المسار الذي يُحوَّل إليه المستخدم بعد تسجيل الدخول.
     */
    public const HOME = '/';

    /**
     * تعريف مسارات التطبيق.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // مسارات API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // مسارات الويب
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
