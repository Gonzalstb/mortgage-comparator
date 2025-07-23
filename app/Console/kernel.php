<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Actualizar tasas de hipoteca cada 6 horas
        $schedule->command('mortgage:update-rates')
            ->everySixHours()
            ->withoutOverlapping()
            ->sendOutputTo(storage_path('logs/mortgage-updates.log'))
            ->emailOutputOnFailure(config('mail.admin_email'));
        
        // Actualización más frecuente durante horas laborales
        $schedule->command('mortgage:update-rates')
            ->weekdays()
            ->hourlyBetween(9, 18)
            ->withoutOverlapping();
        
        // Limpieza de caché antigua
        $schedule->command('cache:prune-stale-tags', ['tag' => 'mortgages'])
            ->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}