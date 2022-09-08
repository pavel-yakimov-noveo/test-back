<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Availability\AvailabilityService;
use App\Services\Availability\Requester\RequesterInterface;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->tag(
            [
                \App\Services\Availability\Requester\Doctolib::class,
                \App\Services\Availability\Requester\ClicRDV::class,
                \App\Services\Availability\Requester\Database::class,
            ],
            [RequesterInterface::class]
        );

        $this->app->bind(AvailabilityService::class, function (ContainerInterface $app) {
            return new AvailabilityService($app->tagged(RequesterInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
