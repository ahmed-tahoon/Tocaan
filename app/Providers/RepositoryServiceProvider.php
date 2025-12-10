<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OrderRepository::class, function ($app) {
            return new OrderRepository(new Order());
        });

        $this->app->singleton(PaymentRepository::class, function ($app) {
            return new PaymentRepository(new Payment());
        });
    }

    public function boot(): void
    {
        //
    }
}
