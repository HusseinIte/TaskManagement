<?php

namespace App\Providers;

use App\Enums\RoleUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('get-all-task', function (User $user) {
            return $user->role == RoleUser::Admin || $user->role==RoleUser::Manager;
        });

        Gate::define('create-task', function (User $user) {
            return $user->role == RoleUser::Admin || $user->role==RoleUser::Manager;
        });

        Gate::define('update-status-task', function (User $user, Task $task) {
            return $user->id == $task->assigned_to ;
        });

        Gate::define('assign-status-task', function (User $user, Task $task) {
            return $user->id == $task->created_by ||  $user->role == RoleUser::Admin;
        });

        Gate::define('delete-task', function (User $user, Task $task) {
            return $user->id == $task->created_by ||  $user->role == RoleUser::Admin;
        });
    }
}
