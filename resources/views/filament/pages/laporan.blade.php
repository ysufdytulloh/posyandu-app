<x-filament-panels::page>

@php
$tabConfig = [
    'timbang'     => ['label' => 'Timbang Balita',      'icon' => 'heroicon-o-scale',        'color' => 'blue'],
    'status_gizi' => ['label' => 'Status Gizi',          'icon' => 'heroicon-o-heart',         'color' => 'green'],
    'imunisasi'   => ['label' => 'Imunisasi',            'icon' => 'heroicon-o-shield-check',  'color' => 'purple'],
    'vitamin_a'   => ['label' => 'Vitamin A',            'icon' => 'heroicon-o-sun',           'color' => 'yellow'],
    'pmt'         => ['label' => 'PMT Distribusi',       'icon' => 'heroicon-o-gift',          'color' => 'orange'],
    'lansia'      => ['label' => 'Pemeriksaan Lansia',   'icon' => 'heroicon-o-user-circle',   'color' => 'red'],
];
$colorMap = [
    'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'active_bg' => 'bg-blue-600',   'border' => 'border-blue-200'],
    'green'  => ['bg' => 'bg-green-50',  'text' => 'text-green-600',  'active_bg' => 'bg-green-600',  'border' => 'border-green-200'],
    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'active_bg' => 'bg-purple-600', 'border' => 'border-purple-200'],
    'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'active_bg' => 'bg-yellow-600', 'border' => 'border-yellow-200'],
    'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'active_bg' => 'bg-orange-600', 'border' => 'border-orange-200'],
    'red'    => ['bg' => 'bg-red-50',    'text' => 'text-red-600',    'active_bg' => 'bg-red-600',    'border' => 'border-red-200'],
];
$active = $tabConfig[$this->activeTab];
$activeColor = $colorMap[$active['color']];
@endphp

{{-- ── TABS ── --}}
<div class="grid grid-cols-3 md:grid-cols-6 gap-3">
    @foreach($tabConfig as $key => $tab)
    @php
        $isActive = $this->activeTab === $key;
        $c = $colorMap[$tab['color']];
    @endphp
    <button
        wire:click="setTab('{{ $key }}')"
        class="flex flex-col items-center gap-2 p-4 rounded-2xl border transition-all duration-200 text-center
            {{ $isActive
                ? $c['active_bg'] . ' border-transparent text-white shadow-lg'
                : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 shadow-sm hover:shadow' }}">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center
            {{ $isActive ? 'bg-white/20' : $c['bg'] }}">
            <x-filament::icon
                :icon="$tab['icon']"
                class="w-5 h-5 {{ $isActive ? 'text-white' : $c['text'] }}"
            />
        </div>
        <span class="text-xs font-semibold leading-tight">{{ $tab['label'] }}</span>
    </button>
    @endforeach
</div>

{{-- ── FILTER ── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

    {{-- Header --}}
    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-50">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $activeColor['bg'] }}">
            <x-filament::icon :icon="$active['icon']" class="w-5 h-5 {{ $activeColor['text'] }}"/>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">{{ $active['label'] }}</p>
            <p class="text-xs text-gray-400">Pilih filter lalu klik Tampilkan Data</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="p-6">
        {{ $this->form }}

        <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-50">
            <x-filament::button
                wire:click="tampilkan"
                icon="heroicon-o-magnifying-glass">
                Tampilkan Data
            </x-filament::button>

             <x-filament::button
                wire:click="resetFilter"
                color="gray"
                icon="heroicon-o-arrow-path">
                Reset
            </x-filament::button>

            @if($this->showPreview)
                <div class="w-px h-5 bg-gray-200"></div>
                <x-filament::button
                    wire:click="exportPdf"
                    color="danger"
                    icon="heroicon-o-document-arrow-down">
                    Export PDF
                </x-filament::button>
                <x-filament::button
                    wire:click="exportExcel"
                    color="success"
                    icon="heroicon-o-table-cells">
                    Export Excel
                </x-filament::button>
            @endif
        </div>
    </div>
</div>

{{-- ── RESULT ── --}}
@if($this->showPreview)
@php $result = $this->getData(); @endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $activeColor['bg'] }}">
                <x-filament::icon icon="heroicon-o-table-cells" class="w-5 h-5 {{ $activeColor['text'] }}"/>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Hasil — {{ $active['label'] }}</p>
                <p class="text-xs text-gray-400">{{ $result['data']->count() }} data ditemukan</p>
            </div>
        </div>

        @if(!$result['data']->isEmpty())
        <span class="px-3 py-1.5 rounded-xl text-xs font-semibold {{ $activeColor['bg'] }} {{ $activeColor['text'] }}">
            {{ $result['data']->count() }} Data
        </span>
        @endif
    </div>

    {{-- Empty --}}
    @if($result['data']->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 gap-3">
        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center">
            <x-filament::icon icon="heroicon-o-inbox" class="w-8 h-8 text-gray-200"/>
        </div>
        <p class="text-sm font-medium text-gray-400">Tidak ada data ditemukan</p>
        <p class="text-xs text-gray-300">Coba ubah filter untuk hasil yang berbeda</p>
    </div>

    {{-- Table --}}
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    @if(in_array($this->activeTab, ['timbang', 'status_gizi']))
                        @foreach(['No','Posyandu','Nama Anak','Tgl Periksa','Berat','Tinggi','Status BB/U','Status TB/U'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'imunisasi')
                        @foreach(['No','Nama Anak','Jenis Imunisasi','Tgl Imunisasi','Kader'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'vitamin_a')
                        @foreach(['No','Posyandu','Nama Anak','Dosis','Tgl Distribusi'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'pmt')
                        @foreach(['No','Posyandu','Jenis PMT','Penerima','Jumlah','Tgl'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'lansia')
                        @foreach(['No','Posyandu','Nama Lansia','Tgl Periksa','Berat','Tinggi','IMT','Tensi'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($result['data'] as $i => $row)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    @if(in_array($this->activeTab, ['timbang', 'status_gizi']))
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->anak?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_periksa)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->berat_kg }} kg</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->tinggi_cm }} cm</td>
                        <td class="py-3.5 px-5">
                            @php $bbU = $row->hasilGizi?->status_bbU ?? '-'; @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                                {{ $bbU === 'Normal' ? 'bg-green-50 text-green-600' :
                                  ($bbU === 'Gizi Kurang' ? 'bg-amber-50 text-amber-600' :
                                  ($bbU === 'Gizi Buruk' ? 'bg-red-50 text-red-600' :
                                  ($bbU === 'Gizi Lebih' ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-400'))) }}">
                                {{ $bbU }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            @php $tbU = $row->hasilGizi?->status_tbU ?? '-'; @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                                {{ $tbU === 'Normal' ? 'bg-green-50 text-green-600' :
                                  ($tbU === 'Pendek' ? 'bg-amber-50 text-amber-600' :
                                  ($tbU === 'Sangat Pendek' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">
                                {{ $tbU }}
                            </span>
                        </td>
                    @elseif($this->activeTab === 'imunisasi')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->anak?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5"><span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg text-xs font-semibold">{{ $row->jenisImunisasi?->nama ?? '-' }}</span></td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_imunisasi)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ $row->kader?->name ?? '-' }}</td>
                    @elseif($this->activeTab === 'vitamin_a')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->anak?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ str_contains($row->dosis, 'Biru') ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }}">
                                {{ $row->dosis }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_distribusi)->format('d/m/Y') }}</td>
                    @elseif($this->activeTab === 'pmt')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5"><span class="px-2.5 py-1 bg-orange-50 text-orange-600 rounded-lg text-xs font-semibold">{{ $row->jenisPmt?->nama ?? '-' }}</span></td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ class_basename($row->penerima_type) }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->jumlah }} {{ $row->satuan }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_distribusi)->format('d/m/Y') }}</td>
                    @elseif($this->activeTab === 'lansia')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->lansia?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_periksa)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->berat_kg }} kg</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs font-medium">{{ $row->tinggi_cm }} cm</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ $row->imt }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ $row->tensi_sistol }}/{{ $row->tensi_diastol }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif

</x-filament-panels::page>
