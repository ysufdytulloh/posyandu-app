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
        // Notifikasi stunting & gizi kurang — setiap tanggal 1 jam 07.00
        $schedule->command('posyandu:notifikasi-kesehatan')
            ->monthlyOn(1, '07:00')
            ->timezone('Asia/Jakarta');

        // Update status jadwal posyandu yang sudah lewat — setiap hari jam 00.01
        $schedule->call(function () {
            \App\Models\JadwalPosyandu::where('tgl_jadwal', '<', now()->toDateString())
                ->where('status', 'aktif')
                ->update(['status' => 'selesai']);
        })->dailyAt('00:01')->timezone('Asia/Jakarta');
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
