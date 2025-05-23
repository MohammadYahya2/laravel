<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use App\Services\Supabase\SupabaseClient;

class SupabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SupabaseClient::class, function ($app) {
            $url = config('supabase.url');
            $key = config('supabase.anon_key') ?: config('supabase.service_key');

            // Logging للتشخيص في بيئة التطوير
            if (app()->environment('local')) {
                Log::info('Supabase URL: ' . $url);
                Log::info('Using key type: ' . (config('supabase.anon_key') ? 'anon' : 'service'));
                Log::info('Key length: ' . strlen($key));
            }

            if (empty($url)) {
                throw new \Exception('Supabase URL is not configured correctly');
            }
            if (empty($key)) {
                throw new \Exception('Supabase API key is not configured correctly');
            }

            return new SupabaseClient($url, $key);
        });

        // singleton للـ Facade
        $this->app->singleton('supabase', function ($app) {
            return $app->make(SupabaseClient::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
