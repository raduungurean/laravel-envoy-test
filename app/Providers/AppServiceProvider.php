<?php

namespace App\Providers;

use App\Repositories\DBUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepository::class, DBUserRepository::class);
    }

    public function boot()
    {

    }
}
