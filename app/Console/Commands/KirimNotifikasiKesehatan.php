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
    protected $description = 'Kirim notifikasi kasus stunting & gizi kurang baru ke admin dan kader';

    public function handle(): void
    {
        // ── NOTIFIKASI ADMIN ──
        $admins = User::where('role', 'admin_desa')->get();

        $stuntingBaru = HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
            ->whereHas('timbang', fn ($q) => $q
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
            )->count();

        $giziKurangBaru = HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
            ->whereHas('timbang', fn ($q) => $q
                ->whereMonth('tgl_periksa', now()->month)
                ->whereYear('tgl_periksa', now()->year)
            )->count();

        foreach ($admins as $admin) {
            if ($stuntingBaru > 0) {
                Notification::make()
                    ->title('⚠️ Kasus Stunting Baru')
                    ->body($stuntingBaru . ' balita terdeteksi stunting bulan ' . now()->translatedFormat('F Y'))
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

            if ($giziKurangBaru > 0) {
                Notification::make()
                    ->title('🚨 Kasus Gizi Kurang Baru')
                    ->body($giziKurangBaru . ' balita terdeteksi gizi kurang/buruk bulan ' . now()->translatedFormat('F Y'))
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

        // ── NOTIFIKASI KADER ──
        $kaders = User::where('role', 'kader')->get();

        foreach ($kaders as $kader) {
            $posyanduId = $kader->posyandu_id;

            $stuntingKader = HasilGizi::whereIn('status_tbU', ['Pendek', 'Sangat Pendek'])
                ->whereHas('timbang', fn ($q) => $q
                    ->where('posyandu_id', $posyanduId)
                    ->whereMonth('tgl_periksa', now()->month)
                    ->whereYear('tgl_periksa', now()->year)
                )->count();

            $giziKurangKader = HasilGizi::whereIn('status_bbU', ['Gizi Kurang', 'Gizi Buruk'])
                ->whereHas('timbang', fn ($q) => $q
                    ->where('posyandu_id', $posyanduId)
                    ->whereMonth('tgl_periksa', now()->month)
                    ->whereYear('tgl_periksa', now()->year)
                )->count();

            if ($stuntingKader > 0) {
                Notification::make()
                    ->title('⚠️ Kasus Stunting Baru')
                    ->body($stuntingKader . ' balita di posyandu Anda terdeteksi stunting bulan ' . now()->translatedFormat('F Y'))
                    ->warning()
                    ->icon('heroicon-o-arrow-trending-down')
                    ->actions([
                        Action::make('lihat')
                            ->label('Lihat Laporan')
                            ->url('/kader/laporan')
                            ->button(),
                    ])
                    ->sendToDatabase($kader);
            }

            if ($giziKurangKader > 0) {
                Notification::make()
                    ->title('🚨 Kasus Gizi Kurang Baru')
                    ->body($giziKurangKader . ' balita di posyandu Anda terdeteksi gizi kurang/buruk bulan ' . now()->translatedFormat('F Y'))
                    ->danger()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->actions([
                        Action::make('lihat')
                            ->label('Lihat Laporan')
                            ->url('/kader/laporan')
                            ->button(),
                    ])
                    ->sendToDatabase($kader);
            }
        }

        $this->info('Notifikasi berhasil dikirim ke admin dan kader!');
    }
}
