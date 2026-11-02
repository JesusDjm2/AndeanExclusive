<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Support\Respaldos;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('avisos:plazos')->dailyAt('07:00')->withoutOverlapping();
Artisan::command('respaldo:crear', fn () => $this->info(Respaldos::crear()))->purpose('Crea una copia de la base de datos');
Schedule::command('respaldo:crear')->dailyAt('03:00')->withoutOverlapping();
