<?php

namespace App\Livewire\Ortu;

use App\Models\Anak;
use App\Models\Imunisasi;
use App\Models\TimbangBalita;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    public array $expanded = [];

    public function toggle(int $anakId): void
    {
        if (in_array($anakId, $this->expanded)) {
            $this->expanded = array_values(array_filter($this->expanded, fn($id) => $id !== $anakId));
        } else {
            $this->expanded[] = $anakId;
        }
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(route('ortu.login'));
    }

   public function render()
    {
        $anakList = Anak::with(['posyandu', 'ibu'])
            ->where('ibu_id', Auth::user()->ibu_id)
            ->get();

        $data = $anakList->map(function ($anak) {
            $timbangTerakhir = TimbangBalita::with('hasilGizi')
                ->where('anak_id', $anak->id)
                ->orderBy('tgl_periksa', 'desc')
                ->first();

            $riwayatTimbang = TimbangBalita::with('hasilGizi')
                ->where('anak_id', $anak->id)
                ->orderBy('tgl_periksa', 'desc')
                ->limit(3)
                ->get();

            $imunisasiTerakhir = Imunisasi::with('jenisImunisasi')
                ->where('anak_id', $anak->id)
                ->orderBy('tgl_imunisasi', 'desc')
                ->limit(2)
                ->get();

            return [
                'anak'             => $anak,
                'timbang_terakhir' => $timbangTerakhir,
                'riwayat_timbang'  => $riwayatTimbang,
                'imunisasi'        => $imunisasiTerakhir,
                'posyandu'         => $anak->posyandu,
            ];
        });

       // Ambil jadwal berikutnya dari database
            $ibu = \App\Models\Ibu::find(auth()->user()->ibu_id);
            $posyanduId = $ibu?->posyandu_id;

            $jadwalBerikutnya = $posyanduId
                ? \App\Models\JadwalPosyandu::where('posyandu_id', $posyanduId)
                    ->whereDate('tgl_jadwal', '>=', now()->toDateString())
                    ->where('status', 'aktif')
                    ->orderBy('tgl_jadwal')
                    ->first()
                : null;


        return view('livewire.ortu.dashboard', [
            'data'             => $data,
            'jadwalBerikutnya' => $jadwalBerikutnya,
        ])->layout('ortu.layouts.app');
    }
}
