<?php

namespace App\Providers;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\DictionaryRepository;
use App\Repositories\Contracts\DictionaryRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Services\Contracts\DictionaryServiceInterface;
use App\Services\DictionaryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DictionaryRepositoryInterface::class, DictionaryRepository::class);
        $this->app->singleton(TransactionRepositoryInterface::class, TransactionRepository::class);

        $this->app->singleton(DictionaryServiceInterface::class, function ($app) {
            return new DictionaryService($app->make(DictionaryRepositoryInterface::class));
        });
    }
}
