<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TeamService;

class TeamServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TeamService::class, function ($app) {
            return new TeamService();
        });
    }

    public function boot()
    {
        //
    }
}
