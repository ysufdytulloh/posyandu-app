<?php

namespace App\Livewire\Ortu;

use App\Models\Kehamilan;
use App\Models\PeriksaKehamilan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DataKehamilanOrtu extends Component
{
    public function render()
    {
        $ibuId = Auth::user()->ibu_id;

        $kehamilan = Kehamilan::with(['periksaKehamilan' => function($q) {
                $q->orderBy('tgl_periksa', 'desc');
            }])
            ->where('ibu_id', $ibuId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.ortu.data-kehamilan-ortu', [
            'kehamilan' => $kehamilan,
        ])->layout('ortu.layouts.app');
    }
}
