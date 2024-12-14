<?php
declare(strict_types=1);

namespace Projecthanif\ExchangeRate;

use Illuminate\Support\ServiceProvider;

class ExchangeRateServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/projecthanif-exchangerate.php' => config_path('projecthanif-exchangerate.php'),
        ], 'projecthanif-exchangerate');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/projecthanif-exchangerate.php',
            'projecthanif-exchangerate'
        );
    }
}