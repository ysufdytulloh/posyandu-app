<x-filament-panels::page>

{{-- FILTER --}}
<x-filament::section>
    <x-slot name="heading">Kartu Kesehatan Lansia</x-slot>
    <x-slot name="description">Riwayat pemeriksaan kesehatan lansia di posyandu</x-slot>
    {{ $this->form }}
</x-filament::section>

@if($this->lansia_id)
@php $data = $this->getLansiaData(); @endphp

@if(empty($data))
    <x-filament::section>
        <div class="flex flex-col items-center justify-center py-12 gap-3">
            <x-filament::icon icon="heroicon-o-inbox" class="w-12 h-12 text-gray-200"/>
            <p class="text-sm text-gray-500 font-medium">Belum ada data pemeriksaan</p>
        </div>
    </x-filament::section>
@else

{{-- INFO LANSIA --}}
<x-filament::section>
    <x-slot name="heading">Informasi Lansia</x-slot>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach([
            ['label' => 'Nama Lansia',      'value' => $data['lansia']['nama'],             'icon' => 'heroicon-o-user',            'color' => 'text-primary-600 bg-primary-50'],
            ['label' => 'Jenis Kelamin',    'value' => $data['lansia']['jk'],               'icon' => 'heroicon-o-identification',  'color' => 'text-blue-600 bg-blue-50'],
            ['label' => 'Tanggal Lahir',    'value' => $data['lansia']['tgl_lahir'],        'icon' => 'heroicon-o-calendar',        'color' => 'text-amber-600 bg-amber-50'],
            ['label' => 'No. HP',           'value' => $data['lansia']['no_hp'],            'icon' => 'heroicon-o-phone',           'color' => 'text-green-600 bg-green-50'],
            ['label' => 'Posyandu',         'value' => $data['lansia']['posyandu'],         'icon' => 'heroicon-o-building-office', 'color' => 'text-teal-600 bg-teal-50'],
            ['label' => 'Kader',            'value' => $data['lansia']['kader'],            'icon' => 'heroicon-o-users',           'color' => 'text-violet-600 bg-violet-50'],
        ] as $info)
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $info['color'] }}">
                <x-filament::icon :icon="$info['icon']" class="w-4 h-4"/>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">{{ $info['label'] }}</p>
                <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $info['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    @if($data['lansia']['alamat'] !== '-' || $data['lansia']['riwayat_penyakit'] !== '-')
    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
        <div>
            <p class="text-xs text-gray-400 font-medium mb-1">Alamat</p>
            <p class="text-sm text-gray-700">{{ $data['lansia']['alamat'] }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 font-medium mb-1">Riwayat Penyakit</p>
            <p class="text-sm text-gray-700">{{ $data['lansia']['riwayat_penyakit'] }}</p>
        </div>
    </div>
    @endif
</x-filament::section>

{{-- RIWAYAT PEMERIKSAAN --}}
<x-filament::section>
    <x-slot name="heading">Riwayat Pemeriksaan</x-slot>
    <x-slot name="description">{{ $data['riwayat']->count() }} data pemeriksaan tercatat</x-slot>

    @if($data['riwayat']->isEmpty())
        <div class="text-center py-8">
            <p class="text-sm text-gray-400">Belum ada data pemeriksaan untuk lansia ini.</p>
        </div>
    @else
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    @foreach(['No','Tgl Periksa','Berat','Tinggi','IMT','Tensi','Gula Darah','Kolesterol','Asam Urat','Keluhan'] as $h)
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['riwayat'] as $i => $r)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-4 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-4 text-gray-600 text-xs whitespace-nowrap">{{ $r->tgl_periksa->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 font-semibold text-gray-700">{{ $r->berat_kg }} kg</td>
                    <td class="py-3 px-4 text-gray-600 text-xs">{{ $r->tinggi_cm }} cm</td>
                    <td class="py-3 px-4">
                        @php
                            $imt = $r->imt;
                            $imtColor = match(true) {
                                $imt < 18.5  => 'bg-amber-50 text-amber-600',
                                $imt <= 24.9 => 'bg-green-50 text-green-600',
                                $imt <= 29.9 => 'bg-amber-50 text-amber-600',
                                default      => 'bg-red-50 text-red-600',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $imtColor }}">
                            {{ number_format($imt, 1) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">
                        @if($r->tensi_sistol && $r->tensi_diastol)
                            <span class="{{ $r->tensi_sistol > 140 ? 'text-red-600 font-bold' : '' }}">
                                {{ $r->tensi_sistol }}/{{ $r->tensi_diastol }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">
                        @if($r->gula_darah)
                            <span class="{{ $r->gula_darah > 200 ? 'text-red-600 font-bold' : '' }}">
                                {{ $r->gula_darah }} mg/dL
                            </span>
                        @else -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">
                        @if($r->kolesterol)
                            <span class="{{ $r->kolesterol > 200 ? 'text-amber-600 font-bold' : '' }}">
                                {{ $r->kolesterol }} mg/dL
                            </span>
                        @else -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">
                        @if($r->asam_urat)
                            <span class="{{ $r->asam_urat > 7 ? 'text-amber-600 font-bold' : '' }}">
                                {{ $r->asam_urat }} mg/dL
                            </span>
                        @else -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $r->keluhan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-filament::section>

@endif
@endif

</x-filament-panels::page>
