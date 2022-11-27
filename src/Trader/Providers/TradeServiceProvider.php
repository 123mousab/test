<?php

namespace Src\Trader\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Trader\Contracts\TraderContract;
use Src\Trader\Domain\Services\TraderService;

class TradeServiceProvider extends ServiceProvider
{
    public $bindings = [
        TraderContract::class => TraderService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__. '/../config/trade.php', 'trade');

        $this->app->register(AuthServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__. '/../routes/auth.php');
        $this->loadRoutesFrom(__DIR__. '/../routes/public.php');
    }
}
