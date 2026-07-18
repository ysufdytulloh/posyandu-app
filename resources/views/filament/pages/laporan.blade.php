<x-filament-panels::page>

@php
$tabConfig = [
    'timbang'      => ['label' => 'Timbang & Status Gizi', 'icon' => 'heroicon-o-scale',             'color' => 'blue'],
    'imunisasi'    => ['label' => 'Imunisasi',              'icon' => 'heroicon-o-shield-check',      'color' => 'purple'],
    'vitamin_a'    => ['label' => 'Vitamin A',              'icon' => 'heroicon-o-sun',               'color' => 'yellow'],
    'pmt'          => ['label' => 'PMT Distribusi',         'icon' => 'heroicon-o-gift',              'color' => 'orange'],
    'lansia'       => ['label' => 'Pemeriksaan Lansia',     'icon' => 'heroicon-o-user-circle',       'color' => 'red'],
    'rekapitulasi' => ['label' => 'Rekapitulasi Desa',      'icon' => 'heroicon-o-building-office-2', 'color' => 'teal'],
    'kehamilan' => ['label' => 'Kehamilan', 'icon' => 'heroicon-o-heart', 'color' => 'pink'],
    'sdidtk'      => ['label' => 'SDIDTK',       'icon' => 'heroicon-o-clipboard-document-check', 'color' => 'violet'],
    'obat_cacing' => ['label' => 'Obat Cacing',   'icon' => 'heroicon-o-beaker',                  'color' => 'lime'],
];
$colorMap = [
    'blue'   => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'active_bg' => 'bg-blue-600',   'border' => 'border-blue-200'],
    'green'  => ['bg' => 'bg-green-50',  'text' => 'text-green-600',  'active_bg' => 'bg-green-600',  'border' => 'border-green-200'],
    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'active_bg' => 'bg-purple-600', 'border' => 'border-purple-200'],
    'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'active_bg' => 'bg-yellow-600', 'border' => 'border-yellow-200'],
    'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'active_bg' => 'bg-orange-600', 'border' => 'border-orange-200'],
    'red'    => ['bg' => 'bg-red-50',    'text' => 'text-red-600',    'active_bg' => 'bg-red-600',    'border' => 'border-red-200'],
    'teal'   => ['bg' => 'bg-teal-50',   'text' => 'text-teal-600',   'active_bg' => 'bg-teal-600',   'border' => 'border-teal-200'],
    'pink' => ['bg' => 'bg-pink-50', 'text' => 'text-pink-600', 'active_bg' => 'bg-pink-600', 'border' => 'border-pink-200'],
    'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'active_bg' => 'bg-violet-600', 'border' => 'border-violet-200'],
    'lime'   => ['bg' => 'bg-lime-50',   'text' => 'text-lime-600',   'active_bg' => 'bg-lime-600',   'border' => 'border-lime-200'],
];
$active      = $tabConfig[$this->activeTab];
$activeColor = $colorMap[$active['color']];
@endphp

{{-- ── TABS ── --}}
<div class="grid grid-cols-3 md:grid-cols-9 gap-3">
    @foreach($tabConfig as $key => $tab)
    @php $isActive = $this->activeTab === $key; $c = $colorMap[$tab['color']]; @endphp
    <button
        wire:click="setTab('{{ $key }}')"
        class="flex flex-col items-center gap-2 p-4 rounded-2xl border transition-all duration-200 text-center
            {{ $isActive
                ? $c['active_bg'] . ' border-transparent text-white shadow-lg'
                : 'bg-white border-gray-100 text-gray-500 hover:border-gray-200 shadow-sm hover:shadow' }}">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $isActive ? 'bg-white/20' : $c['bg'] }}">
            <x-filament::icon :icon="$tab['icon']" class="w-5 h-5 {{ $isActive ? 'text-white' : $c['text'] }}"/>
        </div>
        <span class="text-xs font-semibold leading-tight">{{ $tab['label'] }}</span>
    </button>
    @endforeach
</div>

{{-- ── FILTER ── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

    <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-50">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $activeColor['bg'] }}">
            <x-filament::icon :icon="$active['icon']" class="w-5 h-5 {{ $activeColor['text'] }}"/>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">{{ $active['label'] }}</p>
            <p class="text-xs text-gray-400">Pilih filter lalu klik Tampilkan Data</p>
        </div>
    </div>

    <div class="p-6">
        {{ $this->form }}

        <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-50">
            <x-filament::button wire:click="tampilkan" icon="heroicon-o-magnifying-glass">
                Tampilkan Data
            </x-filament::button>

            @if($this->showPreview)
                <div class="w-px h-5 bg-gray-200"></div>
                <x-filament::button wire:click="exportPdf" color="danger" icon="heroicon-o-document-arrow-down">
                    Export PDF
                </x-filament::button>
                <x-filament::button wire:click="exportExcel" color="success" icon="heroicon-o-table-cells">
                    Export Excel
                </x-filament::button>
            @endif

            <x-filament::button wire:click="resetFilter" color="gray" icon="heroicon-o-arrow-path">
                Reset
            </x-filament::button>
        </div>
    </div>
</div>

{{-- ── RESULT ── --}}
@if($this->showPreview)
@php $result = $this->getData(); @endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

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

    @if($result['data']->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 gap-3">
        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center">
            <x-filament::icon icon="heroicon-o-inbox" class="w-8 h-8 text-gray-200"/>
        </div>
        <p class="text-sm font-medium text-gray-400">Tidak ada data ditemukan</p>
        <p class="text-xs text-gray-300">Coba ubah filter untuk hasil yang berbeda</p>
    </div>

    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    @if($this->activeTab === 'timbang')
                        @foreach(['No','Posyandu','Nama Anak','Tgl Periksa','Berat','Tinggi','Status BB/U','Z-Score BB/U','Status TB/U','Z-Score TB/U'] as $h)
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
                        @foreach(['No','Posyandu','Jenis PMT','Jenis Penerima','Nama Penerima','Jumlah','Tgl'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'lansia')
                        @foreach(['No','Posyandu','Nama Lansia','Tgl Periksa','Berat','Tinggi','IMT','Tensi'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'rekapitulasi')
                        @foreach(['No','Nama Posyandu','Total Balita','Total Lansia','Sudah Timbang','Stunting','Gizi Kurang'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'kehamilan')
                        @foreach(['No','Posyandu','Nama Ibu','HPHT','HPL','Usia Kehamilan','Status'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'sdidtk')
                        @foreach(['No','Posyandu','Nama Anak','Tgl Periksa','Usia','MK','MH','BB','SK','Hasil'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'obat_cacing')
                        @foreach(['No','Posyandu','Nama Anak','Tgl Pemberian','Dosis','Kader'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @elseif($this->activeTab === 'kehamilan')
                        @foreach(['No','Posyandu','Nama Ibu','HPHT','HPL','Status'] as $h)
                            <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($result['data'] as $i => $row)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    @if($this->activeTab === 'timbang')
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
                        <td class="py-3.5 px-5 text-gray-600 text-xs {{ $row->hasilGizi?->bbU_zscore < -2 ? 'text-red-500 font-bold' : '' }}">
                            {{ $row->hasilGizi?->bbU_zscore ? number_format($row->hasilGizi->bbU_zscore, 2) : '-' }}
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
                        <td class="py-3.5 px-5 text-gray-600 text-xs {{ $row->hasilGizi?->tbU_zscore < -2 ? 'text-red-500 font-bold' : '' }}">
                            {{ $row->hasilGizi?->tbU_zscore ? number_format($row->hasilGizi->tbU_zscore, 2) : '-' }}
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
                        <td class="py-3.5 px-5 font-semibold text-gray-700">
                            @php
                                $namaPenerima = match($row->penerima_type) {
                                    'App\Models\Anak'   => \App\Models\Anak::find($row->penerima_id)?->nama ?? '-',
                                    'App\Models\Ibu'    => \App\Models\Ibu::find($row->penerima_id)?->nama ?? '-',
                                    'App\Models\Lansia' => \App\Models\Lansia::find($row->penerima_id)?->nama ?? '-',
                                    default             => '-',
                                };
                            @endphp
                            {{ $namaPenerima }}
                        </td>
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
                    @elseif($this->activeTab === 'rekapitulasi')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->nama }}</td>
                        <td class="py-3.5 px-5 text-gray-600">{{ $row->anak_count }}</td>
                        <td class="py-3.5 px-5 text-gray-600">{{ $row->lansia_count }}</td>
                        <td class="py-3.5 px-5 text-gray-600">{{ $row->total_timbang }}</td>
                        <td class="py-3.5 px-5">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $row->total_stunting > 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                {{ $row->total_stunting }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $row->total_gizi_kurang > 0 ? 'bg-amber-50 text-amber-600' : 'bg-green-50 text-green-600' }}">
                                {{ $row->total_gizi_kurang }}
                            </span>
                        </td>
                    @elseif($this->activeTab === 'kehamilan')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->ibu?->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->ibu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->hpht)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ $row->tgl_perkiraan_lahir ? \Carbon\Carbon::parse($row->tgl_perkiraan_lahir)->format('d/m/Y') : '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ $row->usia_kehamilan ? $row->usia_kehamilan . ' minggu' : '-' }}</td>
                        <td class="py-3.5 px-5">
                            @php $status = $row->status ?? '-'; @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                                {{ $status === 'aktif' ? 'bg-green-50 text-green-600' :
                                ($status === 'melahirkan' ? 'bg-blue-50 text-blue-600' :
                                ($status === 'keguguran' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    @elseif($this->activeTab === 'sdidtk')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->anak?->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->anak?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_periksa)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ $row->usia_bulan }} bln</td>
                        @foreach(['motorik_kasar','motorik_halus','bicara_bahasa','sosial_kemandirian'] as $aspek)
                        <td class="py-3.5 px-5">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                                {{ $row->$aspek === 'S' ? 'bg-green-50 text-green-600' :
                                ($row->$aspek === 'M' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                                {{ $row->$aspek }}
                            </span>
                        </td>
                        @endforeach
                        <td class="py-3.5 px-5">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                                {{ $row->hasil === 'Normal' ? 'bg-green-50 text-green-600' :
                                ($row->hasil === 'Suspek' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                                {{ $row->hasil }}
                            </span>
                        </td>
                    @elseif($this->activeTab === 'obat_cacing')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->anak?->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->anak?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tgl_pemberian)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5"><span class="px-2 py-0.5 bg-lime-50 text-lime-600 rounded-lg text-xs font-semibold">{{ $row->dosis }}</span></td>
                        <td class="py-3.5 px-5 text-gray-600 text-xs">{{ $row->kader?->name ?? '-' }}</td>
                    @elseif($this->activeTab === 'kehamilan')
                        <td class="py-3.5 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs">{{ $row->ibu?->posyandu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 font-semibold text-gray-700">{{ $row->ibu?->nama ?? '-' }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($row->hpht)->format('d/m/Y') }}</td>
                        <td class="py-3.5 px-5 text-gray-500 text-xs whitespace-nowrap">{{ $row->tgl_perkiraan_lahir ? \Carbon\Carbon::parse($row->tgl_perkiraan_lahir)->format('d/m/Y') : '-' }}</td>
                        <td class="py-3.5 px-5">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                                {{ $row->status === 'aktif' ? 'bg-green-50 text-green-600' :
                                ($row->status === 'melahirkan' ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600') }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tabel 2: Detail Pemeriksaan Kehamilan --}}
        @if($this->activeTab === 'kehamilan' && isset($result['periksa']) && $result['periksa']->isNotEmpty())
        <div class="mt-6">
            <div class="px-5 py-3 border-b border-gray-50">
                <p class="text-sm font-semibold text-gray-700">Detail Pemeriksaan (K1-K4)</p>
                <p class="text-xs text-gray-400">{{ $result['periksa']->count() }} data pemeriksaan</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            @foreach(['No','Nama Ibu','Kunjungan','Tgl Periksa','BB (kg)','LILA','TFU','DJJ','HB','Tensi','Status Gizi'] as $h)
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($result['periksa'] as $i => $p)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3 px-4 text-gray-300 text-xs">{{ $i+1 }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-700">{{ $p->kehamilan?->ibu?->nama ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                                    {{ $p->kunjungan_ke === 'K1' ? 'bg-blue-50 text-blue-600' :
                                    ($p->kunjungan_ke === 'K2' ? 'bg-green-50 text-green-600' :
                                    ($p->kunjungan_ke === 'K3' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600')) }}">
                                    {{ $p->kunjungan_ke }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-xs whitespace-nowrap">{{ $p->tgl_periksa->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-700">{{ $p->berat_badan ? $p->berat_badan.' kg' : '-' }}</td>
                            <td class="py-3 px-4 text-gray-600 text-xs {{ $p->lila_cm && $p->lila_cm < 23.5 ? 'text-red-600 font-bold' : '' }}">{{ $p->lila_cm ? $p->lila_cm.' cm' : '-' }}</td>
                            <td class="py-3 px-4 text-gray-600 text-xs">{{ $p->tfu_cm ? $p->tfu_cm.' cm' : '-' }}</td>
                            <td class="py-3 px-4 text-gray-600 text-xs {{ $p->djj && ($p->djj < 120 || $p->djj > 160) ? 'text-red-600 font-bold' : '' }}">{{ $p->djj ? $p->djj.' bpm' : '-' }}</td>
                            <td class="py-3 px-4 text-gray-600 text-xs {{ $p->hb && $p->hb < 11 ? 'text-red-600 font-bold' : '' }}">{{ $p->hb ? $p->hb.' g/dL' : '-' }}</td>
                            <td class="py-3 px-4 text-gray-600 text-xs {{ $p->tensi_sistol && $p->tensi_sistol > 140 ? 'text-red-600 font-bold' : '' }}">
                                {{ $p->tensi_sistol && $p->tensi_diastol ? $p->tensi_sistol.'/'.$p->tensi_diastol : '-' }}
                            </td>
                            <td class="py-3 px-4">
                                @if($p->status_gizi)
                                <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $p->status_gizi === 'Normal' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $p->status_gizi }}
                                </span>
                                @else -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    @endif
</div>
@endif

</x-filament-panels::page>
