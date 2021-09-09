<?php

namespace App\Providers;

use App\Http\IRepositories\ICartRepository;
use App\Http\IRepositories\IProductRepository;
use App\Http\IRepositories\IUserRepository;
use App\Http\Repository\CartRepository;
use App\Http\Repository\ProductRepository;
use App\Http\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(ICartRepository::class, CartRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
