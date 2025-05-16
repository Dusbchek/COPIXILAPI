<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TaskService;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskService::class, function () {
            return new TaskService();
        });
    }

    public function boot(): void
    {
        //
    }
}
