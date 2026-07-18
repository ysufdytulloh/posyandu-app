<x-filament-panels::page>

{{-- FILTER --}}
<x-filament::section>
    <x-slot name="heading">Kartu Ibu Hamil</x-slot>
    <x-slot name="description">Riwayat kehamilan per individu ibu</x-slot>
    {{ $this->form }}
</x-filament::section>

@if($this->ibu_id)
@php $data = $this->getIbuData(); @endphp

@if(empty($data))
    <x-filament::section>
        <div class="flex flex-col items-center justify-center py-12 gap-3">
            <x-filament::icon icon="heroicon-o-inbox" class="w-12 h-12 text-gray-200"/>
            <p class="text-sm text-gray-500 font-medium">Data tidak ditemukan</p>
        </div>
    </x-filament::section>
@else

{{-- INFO IBU --}}
<x-filament::section>
    <x-slot name="heading">Informasi Ibu</x-slot>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach([
            ['label' => 'Nama Ibu',      'value' => $data['ibu']['nama'],     'icon' => 'heroicon-o-user',            'color' => 'text-primary-600 bg-primary-50'],
            ['label' => 'NIK',           'value' => $data['ibu']['nik'],      'icon' => 'heroicon-o-identification',  'color' => 'text-blue-600 bg-blue-50'],
            ['label' => 'Tanggal Lahir', 'value' => $data['ibu']['tgl_lahir'],'icon' => 'heroicon-o-calendar',       'color' => 'text-amber-600 bg-amber-50'],
            ['label' => 'No. HP',        'value' => $data['ibu']['no_hp'],    'icon' => 'heroicon-o-phone',           'color' => 'text-green-600 bg-green-50'],
            ['label' => 'Gol. Darah',    'value' => $data['ibu']['goldar'],   'icon' => 'heroicon-o-heart',           'color' => 'text-red-600 bg-red-50'],
            ['label' => 'Posyandu',      'value' => $data['ibu']['posyandu'], 'icon' => 'heroicon-o-building-office', 'color' => 'text-teal-600 bg-teal-50'],
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

    @if($data['ibu']['alamat'] !== '-')
    <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="text-xs text-gray-400 font-medium mb-1">Alamat</p>
        <p class="text-sm text-gray-700">{{ $data['ibu']['alamat'] }}</p>
    </div>
    @endif
</x-filament::section>

{{-- RIWAYAT KEHAMILAN --}}
<x-filament::section>
    <x-slot name="heading">Riwayat Kehamilan</x-slot>
    <x-slot name="description">{{ $data['kehamilan']->count() }} riwayat kehamilan tercatat</x-slot>

    @if($data['kehamilan']->isEmpty())
        <div class="text-center py-8">
            <p class="text-sm text-gray-400">Belum ada data kehamilan untuk ibu ini.</p>
        </div>
    @else
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    @foreach(['No','HPHT','HPL','Usia Kehamilan','Status','Catatan'] as $h)
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['kehamilan'] as $i => $k)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($k->hpht)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-5 text-gray-600 text-xs whitespace-nowrap">
                        {{ $k->tgl_perkiraan_lahir ? \Carbon\Carbon::parse($k->tgl_perkiraan_lahir)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="py-3 px-5 text-gray-600 text-xs">
                        {{ $k->usia_kehamilan ? $k->usia_kehamilan . ' minggu' : '-' }}
                    </td>
                    <td class="py-3 px-5">
                        @php $status = $k->status ?? '-'; @endphp
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                            {{ $status === 'aktif' ? 'bg-green-50 text-green-600' :
                              ($status === 'melahirkan' ? 'bg-blue-50 text-blue-600' :
                              ($status === 'keguguran' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-gray-500 text-xs">{{ $k->catatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</x-filament::section>

{{-- DETAIL PEMERIKSAAN PER KEHAMILAN --}}
@foreach($data['kehamilan'] as $idx => $k)
@if($k->periksaKehamilan->isNotEmpty())
<x-filament::section>
    <x-slot name="heading">
        Pemeriksaan Kehamilan #{{ $idx + 1 }}
        <span class="text-sm font-normal text-gray-400 ml-2">
            HPHT: {{ \Carbon\Carbon::parse($k->hpht)->format('d/m/Y') }}
            — HPL: {{ $k->tgl_perkiraan_lahir ? \Carbon\Carbon::parse($k->tgl_perkiraan_lahir)->format('d/m/Y') : '-' }}
        </span>
    </x-slot>
    <x-slot name="description">{{ $k->periksaKehamilan->count() }} kunjungan tercatat</x-slot>

    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    @foreach(['No','Tgl Periksa','Kunjungan','Usia','BB (kg)','LILA (cm)','Tensi','Tablet Fe','Status Gizi','Catatan'] as $h)
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($k->periksaKehamilan->sortByDesc('tgl_periksa') as $i => $p)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-4 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-4 text-gray-600 text-xs whitespace-nowrap">{{ $p->tgl_periksa->format('d/m/Y') }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-lg text-xs font-semibold
                            {{ $p->kunjungan_ke === 'K1' ? 'bg-blue-50 text-blue-600' :
                              ($p->kunjungan_ke === 'K2' ? 'bg-green-50 text-green-600' :
                              ($p->kunjungan_ke === 'K3' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600')) }}">
                            {{ $p->kunjungan_ke }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">{{ $p->usia_kehamilan ? $p->usia_kehamilan . ' mgg' : '-' }}</td>
                    <td class="py-3 px-4 font-semibold text-gray-700">{{ $p->berat_badan ? $p->berat_badan . ' kg' : '-' }}</td>
                    <td class="py-3 px-4 text-gray-600 text-xs">
                        @if($p->lila_cm)
                            <span class="{{ $p->lila_cm < 23.5 ? 'text-red-600 font-bold' : '' }}">
                                {{ $p->lila_cm }} cm
                            </span>
                        @else -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">
                        @if($p->tensi_sistol && $p->tensi_diastol)
                            <span class="{{ $p->tensi_sistol > 140 ? 'text-red-600 font-bold' : '' }}">
                                {{ $p->tensi_sistol }}/{{ $p->tensi_diastol }}
                            </span>
                        @else -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-xs">{{ $p->tablet_fe ? $p->tablet_fe . ' tab' : '-' }}</td>
                    <td class="py-3 px-4">
                        @if($p->status_gizi)
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                {{ $p->status_gizi === 'Normal' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                {{ $p->status_gizi }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">-</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $p->catatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif
@endforeach

@endif
@endif

</x-filament-panels::page>
