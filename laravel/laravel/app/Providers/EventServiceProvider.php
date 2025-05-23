<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * خريطة الأحداث والمستمعين (يمكنك تركها فارغة حالياً).
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // مثال:
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
    ];

    /**
     * بوت المزوّد.
     */
    public function boot(): void
    {
        //
    }
}
