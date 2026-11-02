<?php

namespace App\Providers;

use App\Support\Permisos;
use App\Support\ReglasDeAvisos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(): void
    {
        Gate::before(fn ($user) => $user->hasRole(Permisos::ADMIN) ? true : null);
        foreach (Permisos::MATRIZ as $permiso => $roles) {
            Gate::define($permiso, fn ($user) => $user->hasAnyRole($roles));
        }

        ReglasDeAvisos::registrar();

        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $firstName = explode(' ', trim($user->name))[0];
                $view->with('firstName', $firstName);
            } else {
                $view->with('firstName', null);
            }
        });
    }
}
