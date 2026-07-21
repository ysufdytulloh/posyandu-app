<?php

namespace App\Livewire\Ortu;

use App\Models\Anak;
use App\Models\Imunisasi;
use App\Models\Sdidtk;
use App\Models\TimbangBalita;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\VitaminA;
use App\Models\ObatCacing;

class DetailAnak extends Component
{
    public int    $anakId;
    public string $activeTab = 'timbang';

    public function mount(int $id): void
    {
        $anak = Anak::where('id', $id)
            ->where('ibu_id', Auth::user()->ibu_id)
            ->firstOrFail();

        $this->anakId = $anak->id;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $anak = Anak::with(['posyandu', 'ibu'])->find($this->anakId);

        $timbang = TimbangBalita::with('hasilGizi')
            ->where('anak_id', $this->anakId)
            ->orderBy('tgl_periksa', 'desc')
            ->get();

        $imunisasi = Imunisasi::with('jenisImunisasi')
            ->where('anak_id', $this->anakId)
            ->orderBy('tgl_imunisasi', 'desc')
            ->get();

        $sdidtk = Sdidtk::where('anak_id', $this->anakId)
            ->orderBy('tgl_periksa', 'desc')
            ->get();

        $grafikData = TimbangBalita::where('anak_id', $this->anakId)
            ->orderBy('tgl_periksa')
            ->get()
            ->map(fn($t) => [
                'bulan' => $t->tgl_periksa->format('M Y'),
                'bb'    => $t->berat_kg,
                'tb'    => $t->tinggi_cm,
            ]);

        $vitaminA = VitaminA::where('anak_id', $this->anakId)
            ->orderBy('tgl_distribusi', 'desc')
            ->get();

        $obatCacing = ObatCacing::where('anak_id', $this->anakId)
            ->orderBy('tgl_pemberian', 'desc')
            ->get();

        return view('livewire.ortu.detail-anak', [
            'anak'       => $anak,
            'timbang'    => $timbang,
            'imunisasi'  => $imunisasi,
            'sdidtk'     => $sdidtk,
            'grafik'     => $grafikData,
            'vitaminA'   => $vitaminA,
            'obatCacing' => $obatCacing,
        ])->layout('ortu.layouts.app');

    }
}
