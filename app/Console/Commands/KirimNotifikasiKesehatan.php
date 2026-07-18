<?php

namespace App\Console\Commands;

use App\Models\HasilGizi;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Console\Command;

class KirimNotifikasiKesehatan extends Command
{
    protected $signature   = 'posyandu:notifikasi-kesehatan';
    protected $description = 'Kirim notifikasi kasus stunting & gizi kurang baru';

    public function handle(): void
    {
        $admins = User::where('role', 'admin_desa')->get();

        // Stunting baru bulan ini
        $stuntingBaru = HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
            ->whereHas('timbang', fn ($q) => $q
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
            )->with('timbang.anak')
            ->get();

        // Gizi kurang baru bulan ini
        $giziKurangBaru = HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
            ->whereHas('timbang', fn ($q) => $q
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
            )->with('timbang.anak')
            ->get();

        foreach ($admins as $admin) {
            if ($stuntingBaru->count() > 0) {
                Notification::make()
                    ->title('⚠️ Kasus Stunting Baru')
                    ->body($stuntingBaru->count() . ' balita terdeteksi stunting bulan ' . now()->translatedFormat('F Y'))
                    ->warning()
                    ->icon('heroicon-o-arrow-trending-down')
                    ->actions([
                        Action::make('lihat')
                            ->label('Lihat Laporan')
                            ->url('/admin/laporan')
                            ->button(),
                    ])
                    ->sendToDatabase($admin);
            }

            if ($giziKurangBaru->count() > 0) {
                Notification::make()
                    ->title('🚨 Kasus Gizi Kurang Baru')
                    ->body($giziKurangBaru->count() . ' balita terdeteksi gizi kurang/buruk bulan ' . now()->translatedFormat('F Y'))
                    ->danger()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->actions([
                        Action::make('lihat')
                            ->label('Lihat Laporan')
                            ->url('/admin/laporan')
                            ->button(),
                    ])
                    ->sendToDatabase($admin);
            }
        }

        $this->info('Notifikasi berhasil dikirim!');
    }
}
