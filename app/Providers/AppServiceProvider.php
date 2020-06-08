<?php

namespace App\Providers;

use App\Repositories\DBInviteRepository;
use App\Repositories\DBUserRepository;
use App\Repositories\InviteRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepository::class, DBUserRepository::class);
        $this->app->bind(InviteRepository::class, DBInviteRepository::class);
    }

    public function boot()
    {

    }
}
