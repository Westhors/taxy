<?php

namespace App\Providers;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\DistrictRepositoryInterface;
use App\Interfaces\DriverRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\District;
use App\Repositories\AdminRepository;
use App\Repositories\CityRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\DriverRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DriverRepositoryInterface::class, DriverRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(DistrictRepositoryInterface::class, DistrictRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
