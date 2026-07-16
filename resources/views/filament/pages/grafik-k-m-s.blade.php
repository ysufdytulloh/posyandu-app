<x-filament-panels::page>

{{-- FILTER --}}
<x-filament::section>
    <x-slot name="heading">Grafik KMS — Kartu Menuju Sehat</x-slot>
    <x-slot name="description">Kurva pertumbuhan balita berdasarkan standar WHO/Kemenkes</x-slot>

    {{ $this->form }}
</x-filament::section>

@if($this->anak_id)
@php $chart = $this->getChartData(); @endphp

@if(empty($chart))
    <x-filament::section>
        <div class="flex flex-col items-center justify-center py-12 gap-3">
            <x-filament::icon icon="heroicon-o-inbox" class="w-12 h-12 text-gray-200"/>
            <p class="text-sm text-gray-500 font-medium">Belum ada data timbang</p>
            <p class="text-xs text-gray-400">Silakan input data timbang terlebih dahulu</p>
        </div>
    </x-filament::section>
@else

{{-- INFO ANAK --}}
<x-filament::section>
    <x-slot name="heading">Informasi Anak</x-slot>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach([
            ['label' => 'Nama Anak',     'value' => $chart['anak']['nama'],  'icon' => 'heroicon-o-user', 'color' => 'text-primary-600 bg-primary-50'],
            ['label' => 'Jenis Kelamin', 'value' => $chart['anak']['jk'],    'icon' => 'heroicon-o-identification', 'color' => 'text-blue-600 bg-blue-50'],
            ['label' => 'Tanggal Lahir', 'value' => $chart['anak']['lahir'], 'icon' => 'heroicon-o-calendar', 'color' => 'text-amber-600 bg-amber-50'],
            ['label' => 'Nama Ibu',      'value' => $chart['anak']['ibu'],   'icon' => 'heroicon-o-heart', 'color' => 'text-rose-600 bg-rose-50'],
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

<script src="https://unpkg.com/chart.js@4.4.0/dist/chart.umd.min.js"></script>

{{-- CHART --}}
{{-- CHART --}}
<x-filament::section>
    <x-slot name="heading">Grafik Pertumbuhan BB/U</x-slot>
    <x-slot name="description">Berat badan per usia dibandingkan kurva referensi WHO</x-slot>

    <div
        x-data="{
            chart: null,
            init() {
                this.$nextTick(() => {
                    this.drawChart();
                });
            },
            drawChart() {
                const canvas = document.getElementById('kmsChart-{{ $this->anak_id }}');
                if (!canvas) return;
                if (this.chart) { this.chart.destroy(); this.chart = null; }

                const labels     = {{ Js::from($chart['labels']) }};
                const refUsia    = {{ Js::from($chart['refUsia']) }};
                const dataPoints = {{ Js::from($chart['dataPoints']) }};
                const sd3neg     = {{ Js::from($chart['sd3neg']) }};
                const sd2neg     = {{ Js::from($chart['sd2neg']) }};
                const median     = {{ Js::from($chart['median']) }};
                const sd2pos     = {{ Js::from($chart['sd2pos']) }};
                const sd3pos     = {{ Js::from($chart['sd3pos']) }};

                const mappedData = refUsia.map(usia => {
                    const pt = dataPoints.find(p => p.x === usia);
                    return pt ? pt.y : null;
                });

                this.chart = new Chart(canvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            { label:'-3 SD', data:sd3neg, borderColor:'#f87171', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                            { label:'-2 SD', data:sd2neg, borderColor:'#fbbf24', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                            { label:'Median', data:median, borderColor:'#059669', borderWidth:2, pointRadius:0, fill:false, tension:0.4 },
                            { label:'+2 SD', data:sd2pos, borderColor:'#fbbf24', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                            { label:'+3 SD', data:sd3pos, borderColor:'#f87171', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                            {
                                label:'Berat Badan Anak',
                                data: mappedData,
                                borderColor:'#059669',
                                backgroundColor:'#059669',
                                borderWidth:2.5,
                                pointRadius:6,
                                pointBackgroundColor:'#059669',
                                pointBorderColor:'#fff',
                                pointBorderWidth:2.5,
                                fill:false,
                                tension:0.3,
                                spanGaps:false,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        interaction: { mode:'index', intersect:false },
                        plugins: {
                            legend: { display:false },
                            tooltip: {
                                backgroundColor:'#fff',
                                titleColor:'#374151',
                                bodyColor:'#6b7280',
                                borderColor:'#e5e7eb',
                                borderWidth:1,
                                padding:10,
                                callbacks: {
                                    label: c => c.dataset.label + ': ' + (c.raw !== null ? c.raw + ' kg' : '-'),
                                }
                            },
                        },
                        scales: {
                            x: { grid:{ color:'#f8fafc' }, ticks:{ font:{ size:11 }, color:'#94a3b8' } },
                            y: {
                                grid:{ color:'#f8fafc' },
                                ticks:{ font:{ size:11 }, color:'#94a3b8', callback: v => v + ' kg' },
                                title:{ display:true, text:'Berat Badan (kg)', color:'#94a3b8', font:{ size:11 } },
                            },
                        },
                    },
                });
            }
        }"
    >
        <div class="mb-4" style="height: 350px;">
            <canvas id="kmsChart-{{ $this->anak_id }}" style="width:100%;height:100%;"></canvas>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap gap-x-6 gap-y-2 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-2"><div class="w-5 h-0.5 bg-red-400 opacity-70" style="border-top: 2px dashed #f87171;"></div><span class="text-xs text-gray-500">-3 SD — Gizi Buruk</span></div>
        <div class="flex items-center gap-2"><div class="w-5 h-0.5" style="border-top: 2px dashed #fbbf24;"></div><span class="text-xs text-gray-500">-2 SD — Gizi Kurang</span></div>
        <div class="flex items-center gap-2"><div class="w-5 h-0.5 bg-green-500"></div><span class="text-xs text-gray-500">Median — Normal</span></div>
        <div class="flex items-center gap-2"><div class="w-5 h-0.5" style="border-top: 2px dashed #fbbf24;"></div><span class="text-xs text-gray-500">+2 SD — Gizi Lebih</span></div>
        <div class="flex items-center gap-2"><div class="w-5 h-0.5" style="border-top: 2px dashed #f87171;"></div><span class="text-xs text-gray-500">+3 SD</span></div>
        <div class="flex items-center gap-2"><div class="w-5 h-0.5 bg-primary-600"></div><span class="text-xs text-gray-500 font-semibold">Data Timbang Anak</span></div>
    </div>
</x-filament::section>

{{-- RIWAYAT TIMBANG --}}
<x-filament::section>
    <x-slot name="heading">Riwayat Timbang</x-slot>
    <x-slot name="description">{{ count($chart['dataPoints']) }} data timbang tercatat</x-slot>

    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    @foreach(['No','Tgl Periksa','Usia','Berat (kg)','Tinggi (cm)','Status BB/U','Status TB/U'] as $h)
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $timbangList = \App\Models\TimbangBalita::with('hasilGizi')
                        ->where('anak_id', $this->anak_id)
                        ->orderBy('tgl_periksa')
                        ->get();
                @endphp
                @foreach($timbangList as $i => $t)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-3.5 px-6 text-gray-300 text-xs">{{ $i+1 }}</td>
                    <td class="py-3.5 px-6 text-gray-600 text-xs whitespace-nowrap">{{ $t->tgl_periksa->format('d/m/Y') }}</td>
                    <td class="py-3.5 px-6 text-gray-600 text-xs">{{ $chart['dataPoints'][$i]['x'] }} bulan</td>
                    <td class="py-3.5 px-6 font-bold text-gray-700">{{ $t->berat_kg }} kg</td>
                    <td class="py-3.5 px-6 text-gray-600 text-xs">{{ $t->tinggi_cm }} cm</td>
                    <td class="py-3.5 px-6">
                        @php $bbU = $t->hasilGizi?->status_bbU ?? '-'; @endphp
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                            {{ $bbU === 'Normal' ? 'bg-green-50 text-green-600' :
                              ($bbU === 'Gizi Kurang' ? 'bg-amber-50 text-amber-600' :
                              ($bbU === 'Gizi Buruk' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">
                            {{ $bbU }}
                        </span>
                    </td>
                    <td class="py-3.5 px-6">
                        @php $tbU = $t->hasilGizi?->status_tbU ?? '-'; @endphp
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                            {{ $tbU === 'Normal' ? 'bg-green-50 text-green-600' :
                              ($tbU === 'Pendek' ? 'bg-amber-50 text-amber-600' :
                              ($tbU === 'Sangat Pendek' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400')) }}">
                            {{ $tbU }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>

@push('scripts')
<script>
(function() {
    const canvasId = 'kmsChart-{{ $this->anak_id }}';

    function drawChart() {
        const canvas = document.getElementById(canvasId);
        if (!canvas) { setTimeout(drawChart, 200); return; }
        if (typeof Chart === 'undefined') { setTimeout(drawChart, 200); return; }

        // Destroy semua instance lama
        Object.values(Chart.instances || {}).forEach(c => {
            try { c.destroy(); } catch(e) {}
        });

        const labels     = @json($chart['labels']);
        const refUsia    = @json($chart['refUsia']);
        const dataPoints = @json($chart['dataPoints']);
        const sd3neg     = @json($chart['sd3neg']);
        const sd2neg     = @json($chart['sd2neg']);
        const median     = @json($chart['median']);
        const sd2pos     = @json($chart['sd2pos']);
        const sd3pos     = @json($chart['sd3pos']);

        const mappedData = refUsia.map(usia => {
            const pt = dataPoints.find(p => p.x === usia);
            return pt ? pt.y : null;
        });

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label:'-3 SD', data:sd3neg, borderColor:'#f87171', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                    { label:'-2 SD', data:sd2neg, borderColor:'#fbbf24', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                    { label:'Median', data:median, borderColor:'#059669', borderWidth:2, pointRadius:0, fill:false, tension:0.4 },
                    { label:'+2 SD', data:sd2pos, borderColor:'#fbbf24', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                    { label:'+3 SD', data:sd3pos, borderColor:'#f87171', borderWidth:1.5, borderDash:[5,5], pointRadius:0, fill:false, tension:0.4 },
                    {
                        label:'Berat Badan Anak',
                        data: mappedData,
                        borderColor:'#059669',
                        backgroundColor:'#059669',
                        borderWidth:2.5,
                        pointRadius:6,
                        pointBackgroundColor:'#059669',
                        pointBorderColor:'#fff',
                        pointBorderWidth:2.5,
                        fill:false,
                        tension:0.3,
                        spanGaps:false,
                    },
                ],
            },
            options: {
                responsive: true,
                interaction: { mode:'index', intersect:false },
                plugins: {
                    legend: { display:false },
                    tooltip: {
                        backgroundColor:'#fff',
                        titleColor:'#374151',
                        bodyColor:'#6b7280',
                        borderColor:'#e5e7eb',
                        borderWidth:1,
                        padding:10,
                        callbacks: {
                            label: c => c.dataset.label + ': ' + (c.raw !== null ? c.raw + ' kg' : '-'),
                        }
                    },
                },
                scales: {
                    x: { grid:{ color:'#f8fafc' }, ticks:{ font:{ size:11 }, color:'#94a3b8' } },
                    y: {
                        grid:{ color:'#f8fafc' },
                        ticks:{ font:{ size:11 }, color:'#94a3b8', callback: v => v+' kg' },
                        title:{ display:true, text:'Berat Badan (kg)', color:'#94a3b8', font:{ size:11 } },
                    },
                },
            },
        });
    }

    drawChart();
})();
</script>
@endpush

@endif
@endif

</x-filament-panels::page>
