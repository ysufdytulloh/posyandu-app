
<x-filament-panels::page>

{{-- FILTER --}}
<x-filament::section>
    <x-slot name="heading">Kartu Anak</x-slot>
    <x-slot name="description">Rekap seluruh data kesehatan per anak</x-slot>
    {{ $this->form }}
</x-filament::section>

@if($this->anak_id)
@php $data = $this->getAnakData(); @endphp

@if(empty($data))
    <x-filament::section>
        <div class="flex flex-col items-center justify-center py-12 gap-3">
            <x-filament::icon icon="heroicon-o-inbox" class="w-12 h-12 text-gray-200"/>
            <p class="text-sm text-gray-500">Data tidak ditemukan</p>
        </div>
    </x-filament::section>
@else

{{-- INFO ANAK --}}
<x-filament::section>
    <x-slot name="heading">Informasi Anak</x-slot>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach([
            ['label'=>'Nama Anak',     'value'=>$data['anak']['nama'],     'icon'=>'heroicon-o-user',            'color'=>'text-primary-600 bg-primary-50'],
            ['label'=>'Jenis Kelamin', 'value'=>$data['anak']['jk'],       'icon'=>'heroicon-o-identification',  'color'=>'text-blue-600 bg-blue-50'],
            ['label'=>'Tanggal Lahir', 'value'=>$data['anak']['tgl_lahir'],'icon'=>'heroicon-o-calendar',        'color'=>'text-amber-600 bg-amber-50'],
            ['label'=>'Nama Ibu',      'value'=>$data['anak']['ibu'],      'icon'=>'heroicon-o-heart',           'color'=>'text-rose-600 bg-rose-50'],
            ['label'=>'Posyandu',      'value'=>$data['anak']['posyandu'], 'icon'=>'heroicon-o-building-office', 'color'=>'text-teal-600 bg-teal-50'],
            ['label'=>'Kader',         'value'=>$data['anak']['kader'],    'icon'=>'heroicon-o-users',           'color'=>'text-violet-600 bg-violet-50'],
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
</x-filament::section>

{{-- TIMBANG --}}
@if($data['timbang']->isNotEmpty())
<x-filament::section>
    <x-slot name="heading">Riwayat Timbang</x-slot>
    <x-slot name="description">{{ $data['timbang']->count() }} data</x-slot>
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50">
                @foreach(['No','Tgl Periksa','Berat (kg)','Tinggi (cm)','Status BB/U','Status TB/U'] as $h)
                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['timbang'] as $i => $t)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-3 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $t->tgl_periksa->format('d/m/Y') }}</td>
                    <td class="py-3 px-5 font-bold text-gray-700">{{ $t->berat_kg }} kg</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $t->tinggi_cm }} cm</td>
                    <td class="py-3 px-5">
                        @php $bbU = $t->hasilGizi?->status_bbU ?? '-'; @endphp
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $bbU === 'Normal' ? 'bg-green-50 text-green-600' : ($bbU === 'Gizi Kurang' ? 'bg-amber-50 text-amber-600' : ($bbU === 'Gizi Buruk' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">{{ $bbU }}</span>
                    </td>
                    <td class="py-3 px-5">
                        @php $tbU = $t->hasilGizi?->status_tbU ?? '-'; @endphp
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $tbU === 'Normal' ? 'bg-green-50 text-green-600' : ($tbU === 'Pendek' ? 'bg-amber-50 text-amber-600' : ($tbU === 'Sangat Pendek' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">{{ $tbU }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

{{-- IMUNISASI --}}
@if($data['imunisasi']->isNotEmpty())
<x-filament::section>
    <x-slot name="heading">Riwayat Imunisasi</x-slot>
    <x-slot name="description">{{ $data['imunisasi']->count() }} data</x-slot>
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50">
                @foreach(['No','Jenis Imunisasi','Tgl Imunisasi','Kader'] as $h)
                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['imunisasi'] as $i => $im)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-3 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-5"><span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold">{{ $im->jenisImunisasi?->nama ?? '-' }}</span></td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $im->tgl_imunisasi->format('d/m/Y') }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $im->kader?->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

{{-- SDIDTK --}}
@if($data['sdidtk']->isNotEmpty())
<x-filament::section>
    <x-slot name="heading">Riwayat SDIDTK</x-slot>
    <x-slot name="description">{{ $data['sdidtk']->count() }} data</x-slot>
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50">
                @foreach(['No','Tgl Periksa','Usia','MK','MH','BB','SK','Hasil'] as $h)
                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['sdidtk'] as $i => $s)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-3 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $s->tgl_periksa->format('d/m/Y') }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $s->usia_bulan }} bln</td>
                    @foreach(['motorik_kasar','motorik_halus','bicara_bahasa','sosial_kemandirian'] as $aspek)
                    <td class="py-3 px-5">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $s->$aspek === 'S' ? 'bg-green-50 text-green-600' : ($s->$aspek === 'M' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">{{ $s->$aspek }}</span>
                    </td>
                    @endforeach
                    <td class="py-3 px-5">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $s->hasil === 'Normal' ? 'bg-green-50 text-green-600' : ($s->hasil === 'Suspek' ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">{{ $s->hasil }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

{{-- VITAMIN A --}}
@if($data['vitamin_a']->isNotEmpty())
<x-filament::section>
    <x-slot name="heading">Riwayat Vitamin A</x-slot>
    <x-slot name="description">{{ $data['vitamin_a']->count() }} data</x-slot>
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50">
                @foreach(['No','Tgl Distribusi','Dosis','Kader'] as $h)
                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['vitamin_a'] as $i => $v)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-3 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $v->tgl_distribusi->format('d/m/Y') }}</td>
                    <td class="py-3 px-5"><span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ str_contains($v->dosis, 'Biru') ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }}">{{ $v->dosis }}</span></td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $v->kader?->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

{{-- OBAT CACING --}}
@if($data['obat_cacing']->isNotEmpty())
<x-filament::section>
    <x-slot name="heading">Riwayat Obat Cacing</x-slot>
    <x-slot name="description">{{ $data['obat_cacing']->count() }} data</x-slot>
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50">
                @foreach(['No','Tgl Pemberian','Dosis','Keterangan','Kader'] as $h)
                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $h }}</th>
                @endforeach
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($data['obat_cacing'] as $i => $o)
                <tr class="hover:bg-gray-50/50">
                    <td class="py-3 px-5 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $o->tgl_pemberian->format('d/m/Y') }}</td>
                    <td class="py-3 px-5"><span class="px-2 py-0.5 bg-lime-50 text-lime-600 rounded-lg text-xs font-semibold">{{ $o->dosis }}</span></td>
                    <td class="py-3 px-5 text-gray-500 text-xs">{{ $o->keterangan ?? '-' }}</td>
                    <td class="py-3 px-5 text-gray-600 text-xs">{{ $o->kader?->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
@endif

@endif
@endif

</x-filament-panels::page>
