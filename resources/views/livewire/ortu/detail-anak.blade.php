<div style="max-width:480px; margin:0 auto; min-height:100vh; background:#f8f7ff; font-family:'Inter',sans-serif;">

    {{-- FIXED HEADER --}}
    <div style="position:fixed; top:0; left:50%; transform:translateX(-50%); width:100%; max-width:480px; z-index:100;">
        <div style="background:#5b21b6; padding:16px 16px 24px; border-radius:0 0 28px 28px; position:relative; overflow:hidden;">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>

            {{-- Back + Title --}}
            <div style="display:flex; align-items:center; gap:12px; position:relative; margin-bottom:14px;">
                <a href="{{ route('ortu.dashboard') }}"
                   style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:11px; color:rgba(255,255,255,0.6); margin:0;">Detail Anak</p>
                    <p style="font-size:17px; font-weight:700; color:#fff; margin:3px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $anak->nama }}</p>
                </div>
                <div style="width:40px; height:40px; border-radius:12px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; color:#fff; border:1.5px solid rgba(255,255,255,0.2); flex-shrink:0;">
                    {{ strtoupper(substr($anak->nama, 0, 2)) }}
                </div>
            </div>

            {{-- Floating biodata card --}}
            <div style="background:#fff; border-radius:16px; padding:12px 14px; box-shadow:0 8px 24px rgba(91,33,182,0.2); position:relative;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0;">
                    <div style="padding:0 12px 10px 0; border-bottom:1px solid #f3f4f6; border-right:1px solid #f3f4f6;">
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Jenis kelamin</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:3px 0 0;">{{ $anak->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div style="padding:0 0 10px 12px; border-bottom:1px solid #f3f4f6;">
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Tanggal lahir</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:3px 0 0;">{{ $anak->tgl_lahir?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                    <div style="padding:10px 12px 0 0; border-right:1px solid #f3f4f6;">
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Usia</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:3px 0 0;">{{ $anak->tgl_lahir?->diffInMonths(now()) ?? '-' }} bulan</p>
                    </div>
                    <div style="padding:10px 0 0 12px;">
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Posyandu</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:3px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $anak->posyandu?->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div style="padding:230px 14px 90px;">

        {{-- TABS --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr 1fr; gap:6px; margin-bottom:14px;">
            @foreach([
                ['key'=>'timbang',    'label'=>'Timbang'],
                ['key'=>'imunisasi',  'label'=>'Imunisasi'],
                ['key'=>'sdidtk',     'label'=>'SDIDTK'],
                ['key'=>'vitamin',    'label'=>'Vit. A'],
                ['key'=>'obatcacing', 'label'=>'Cacing'],
            ] as $tab)
            <button wire:click="setTab('{{ $tab['key'] }}')"
                    style="padding:10px 8px; border-radius:12px; border:{{ $activeTab === $tab['key'] ? '2px solid #7c3aed' : '1px solid #ede9fe' }}; background:{{ $activeTab === $tab['key'] ? '#7c3aed' : '#fff' }}; color:{{ $activeTab === $tab['key'] ? '#fff' : '#8b5cf6' }}; font-size:12px; font-weight:600; cursor:pointer;">
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- TAB TIMBANG --}}
        @if($activeTab === 'timbang')

        {{-- STAT BB & TB --}}
        @if($timbang->isNotEmpty())
        @php $t = $timbang->first(); $statusBB = $t->hasilGizi?->status_bbU ?? '-'; $statusTB = $t->hasilGizi?->status_tbU ?? '-'; @endphp
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
            <div style="background:#fff; border-radius:16px; padding:14px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <div style="width:30px; height:30px; border-radius:8px; background:#ede9fe; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <p style="font-size:11px; color:#8b5cf6; margin:0; font-weight:500;">Berat badan</p>
                </div>
                <p style="font-size:24px; font-weight:700; color:#1e1b4b; margin:0 0 6px;">{{ $t->berat_kg }}<span style="font-size:12px; color:#8b5cf6; font-weight:500;"> kg</span></p>
                <span style="font-size:11px; padding:3px 10px; border-radius:20px; font-weight:600;
                    background:{{ $statusBB === 'Normal' ? '#eaf3de' : '#fee2e2' }};
                    color:{{ $statusBB === 'Normal' ? '#3b6d11' : '#991b1b' }};">{{ $statusBB }} ✓</span>
            </div>
            <div style="background:#fff; border-radius:16px; padding:14px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <div style="width:30px; height:30px; border-radius:8px; background:#ede9fe; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5"><line x1="12" y1="2" x2="12" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <p style="font-size:11px; color:#8b5cf6; margin:0; font-weight:500;">Tinggi badan</p>
                </div>
                <p style="font-size:24px; font-weight:700; color:#1e1b4b; margin:0 0 6px;">{{ $t->tinggi_cm }}<span style="font-size:12px; color:#8b5cf6; font-weight:500;"> cm</span></p>
                <span style="font-size:11px; padding:3px 10px; border-radius:20px; font-weight:600;
                    background:{{ $statusTB === 'Normal' ? '#eaf3de' : '#fee2e2' }};
                    color:{{ $statusTB === 'Normal' ? '#3b6d11' : '#991b1b' }};">{{ $statusTB }} ✓</span>
            </div>
        </div>
        @endif

        {{-- GRAFIK --}}
        @if($grafik->isNotEmpty())
        @php
            $bbTerakhir = $grafik->last()['bb'] ?? 0;
            $bbPertama  = $grafik->first()['bb'] ?? 0;
            $tren       = round($bbTerakhir - $bbPertama, 1);
        @endphp
        <div style="background:#fff; border-radius:16px; padding:14px 16px; margin-bottom:12px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0;">Grafik berat badan</p>
                <span style="font-size:11px; padding:2px 8px; border-radius:8px; font-weight:600;
                    background:{{ $tren >= 0 ? '#eaf3de' : '#fee2e2' }};
                    color:{{ $tren >= 0 ? '#3b6d11' : '#991b1b' }};">
                    {{ $tren >= 0 ? '↑ Naik' : '↓ Turun' }}
                </span>
            </div>
            <canvas id="bbChart" height="100"></canvas>
            <p style="font-size:11px; color:#9ca3af; margin:10px 0 0; padding-top:10px; border-top:1px solid #f3f4f6;">
                Tren {{ $tren >= 0 ? 'naik' : 'turun' }} {{ abs($tren) }} kg dalam {{ $grafik->count() }} bulan terakhir
            </p>
        </div>
        @endif

        {{-- RIWAYAT TIMBANG --}}
        <div style="background:#fff; border-radius:16px; padding:14px 16px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0;">Riwayat timbang</p>
                <span style="font-size:11px; color:#8b5cf6; background:#f5f3ff; padding:3px 9px; border-radius:8px; font-weight:500;">{{ $timbang->count() }} data</span>
            </div>
            @forelse($timbang as $r)
            @php $stt = $r->hasilGizi?->status_bbU ?? '-'; $sBg = $stt === 'Normal' ? '#eaf3de' : '#fee2e2'; $sTxt = $stt === 'Normal' ? '#3b6d11' : '#991b1b'; @endphp
            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; background:#faf9ff; border-radius:12px; border:1px solid #ede9fe; margin-bottom:8px;">
                <div>
                    <p style="font-size:12px; color:#9ca3af; margin:0;">{{ $r->tgl_periksa->translatedFormat('d M Y') }}</p>
                    <p style="font-size:14px; font-weight:600; color:#1e1b4b; margin:2px 0 0;">{{ $r->berat_kg }} kg &nbsp;•&nbsp; {{ $r->tinggi_cm }} cm</p>
                    @if($r->hasilGizi?->status_tbU)
                    <p style="font-size:11px; color:#9ca3af; margin:2px 0 0;">TB/U: {{ $r->hasilGizi->status_tbU }}</p>
                    @endif
                </div>
                <span style="font-size:11px; padding:3px 10px; border-radius:20px; font-weight:600; flex-shrink:0; background:{{ $sBg }}; color:{{ $sTxt }};">{{ $stt }}</span>
            </div>
            @empty
            <p style="text-align:center; color:#9ca3af; font-size:13px; padding:20px 0; margin:0;">Belum ada data timbang</p>
            @endforelse
        </div>
        @endif

        {{-- TAB IMUNISASI --}}
        @if($activeTab === 'imunisasi')
        <div style="background:#fff; border-radius:16px; padding:14px 16px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0;">Semua imunisasi</p>
                <span style="font-size:11px; color:#8b5cf6; background:#f5f3ff; padding:3px 9px; border-radius:8px; font-weight:500;">{{ $imunisasi->count() }} data</span>
            </div>
            @forelse($imunisasi as $im)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#faf9ff; border-radius:12px; border:1px solid #ede9fe; margin-bottom:8px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:10px; background:#eaf3de; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b6d11" stroke-width="2" stroke-linecap="round"><path d="M18 2l4 4-10 10H8v-4L18 2z"/><path d="M8 16l-5 5"/></svg>
                    </div>
                    <div>
                        <p style="font-size:13px; color:#1e1b4b; margin:0; font-weight:600;">{{ $im->jenisImunisasi?->nama ?? '-' }}</p>
                        <p style="font-size:11px; color:#9ca3af; margin:2px 0 0;">{{ $im->tgl_imunisasi->format('d M Y') }}</p>
                        @if($im->keterangan)
                        <p style="font-size:11px; color:#8b5cf6; margin:2px 0 0;">{{ $im->keterangan }}</p>
                        @endif
                    </div>
                </div>
                <span style="font-size:11px; background:#eaf3de; color:#3b6d11; padding:3px 10px; border-radius:20px; font-weight:600; flex-shrink:0;">Selesai</span>
            </div>
            @empty
            <p style="text-align:center; color:#9ca3af; font-size:13px; padding:20px 0; margin:0;">Belum ada data imunisasi</p>
            @endforelse
        </div>
        @endif

        {{-- TAB SDIDTK --}}
        @if($activeTab === 'sdidtk')
        <div style="background:#fff; border-radius:16px; padding:14px 16px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0;">Data SDIDTK</p>
                <span style="font-size:11px; color:#8b5cf6; background:#f5f3ff; padding:3px 9px; border-radius:8px; font-weight:500;">{{ $sdidtk->count() }} data</span>
            </div>
            @forelse($sdidtk as $s)
            <div style="padding:12px 14px; background:#faf9ff; border-radius:12px; border:1px solid #ede9fe; margin-bottom:8px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <div>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">{{ $s->tgl_periksa->format('d M Y') }}</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:2px 0 0;">Usia {{ $s->usia_bulan }} bulan</p>
                    </div>
                    <span style="font-size:12px; padding:4px 12px; border-radius:20px; font-weight:600; flex-shrink:0;
                        background:{{ $s->hasil === 'Normal' ? '#eaf3de' : ($s->hasil === 'Suspek' ? '#fef3c7' : '#fee2e2') }};
                        color:{{ $s->hasil === 'Normal' ? '#3b6d11' : ($s->hasil === 'Suspek' ? '#92400e' : '#991b1b') }};">
                        {{ $s->hasil }}
                    </span>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px;">
                    @foreach([
                        ['label'=>'Motorik kasar',   'value'=>$s->motorik_kasar],
                        ['label'=>'Motorik halus',   'value'=>$s->motorik_halus],
                        ['label'=>'Bicara & bahasa', 'value'=>$s->bicara_bahasa],
                        ['label'=>'Sosial',          'value'=>$s->sosial_kemandirian],
                    ] as $aspek)
                    <div style="display:flex; align-items:center; justify-content:space-between; background:#fff; border-radius:8px; padding:6px 10px; border:1px solid #ede9fe;">
                        <p style="font-size:11px; color:#9ca3af; margin:0;">{{ $aspek['label'] }}</p>
                        <span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px;
                            background:{{ $aspek['value'] === 'S' ? '#eaf3de' : ($aspek['value'] === 'M' ? '#fef3c7' : '#fee2e2') }};
                            color:{{ $aspek['value'] === 'S' ? '#3b6d11' : ($aspek['value'] === 'M' ? '#92400e' : '#991b1b') }};">
                            {{ $aspek['value'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @if($s->catatan)
                <p style="font-size:11px; color:#6b7280; margin:8px 0 0; padding-top:8px; border-top:1px solid #f3f4f6;">{{ $s->catatan }}</p>
                @endif
            </div>
            @empty
            <p style="text-align:center; color:#9ca3af; font-size:13px; padding:20px 0; margin:0;">Belum ada data SDIDTK</p>
            @endforelse
        </div>
        @endif

        {{-- TAB VITAMIN A --}}
        @if($activeTab === 'vitamin')
        <div style="background:#fff; border-radius:16px; padding:14px 16px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0;">Vitamin A</p>
                <span style="font-size:11px; color:#8b5cf6; background:#f5f3ff; padding:3px 9px; border-radius:8px; font-weight:500;">{{ $vitaminA->count() }} data</span>
            </div>
            @forelse($vitaminA as $v)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#faf9ff; border-radius:12px; border:1px solid #ede9fe; margin-bottom:8px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:10px; background:#fef3c7; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:16px;">💊</div>
                    <div>
                        <p style="font-size:13px; color:#1e1b4b; margin:0; font-weight:600;">{{ $v->dosis }}</p>
                        <p style="font-size:11px; color:#9ca3af; margin:2px 0 0;">{{ $v->tgl_distribusi->format('d M Y') }}</p>
                    </div>
                </div>
                <span style="font-size:11px; background:#eaf3de; color:#3b6d11; padding:3px 10px; border-radius:20px; font-weight:600; flex-shrink:0;">Diberikan</span>
            </div>
            @empty
            <p style="text-align:center; color:#9ca3af; font-size:13px; padding:20px 0; margin:0;">Belum ada data Vitamin A</p>
            @endforelse
        </div>
        @endif

        {{-- TAB OBAT CACING --}}
        @if($activeTab === 'obatcacing')
        <div style="background:#fff; border-radius:16px; padding:14px 16px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(91,33,182,0.07);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0;">Obat Cacing</p>
                <span style="font-size:11px; color:#8b5cf6; background:#f5f3ff; padding:3px 9px; border-radius:8px; font-weight:500;">{{ $obatCacing->count() }} data</span>
            </div>
            @forelse($obatCacing as $o)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#faf9ff; border-radius:12px; border:1px solid #ede9fe; margin-bottom:8px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; border-radius:10px; background:#fce7f3; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:16px;">🔴</div>
                    <div>
                        <p style="font-size:13px; color:#1e1b4b; margin:0; font-weight:600;">{{ $o->dosis }}</p>
                        <p style="font-size:11px; color:#9ca3af; margin:2px 0 0;">{{ $o->tgl_pemberian->format('d M Y') }}</p>
                    </div>
                </div>
                <span style="font-size:11px; background:#eaf3de; color:#3b6d11; padding:3px 10px; border-radius:20px; font-weight:600; flex-shrink:0;">Diberikan</span>
            </div>
            @empty
            <p style="text-align:center; color:#9ca3af; font-size:13px; padding:20px 0; margin:0;">Belum ada data Obat Cacing</p>
            @endforelse
        </div>
        @endif

    </div>

    {{-- FIXED FOOTER --}}
    <div style="position:fixed; bottom:0; left:50%; transform:translateX(-50%); width:100%; max-width:480px; background:#f8f7ff; padding:12px 14px 16px; z-index:100;">
        <a href="{{ route('ortu.dashboard') }}"
           style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:13px; border-radius:14px; border:1.5px solid #ddd6fe; background:#fff; color:#7c3aed; font-size:13px; font-weight:600; text-decoration:none; box-sizing:border-box;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Beranda
        </a>
        <p style="text-align:center; font-size:11px; color:#c4b5fd; margin:8px 0 0;">
            &copy; {{ date('Y') }} Sistem Informasi Posyandu
        </p>
    </div>

</div>

@if($grafik->isNotEmpty() && $activeTab === 'timbang')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', initChart);
document.addEventListener('livewire:updated', initChart);
document.addEventListener('livewire:load', initChart);
setTimeout(initChart, 300);
function initChart() {
    var ctx = document.getElementById('bbChart');
    if (!ctx) return;
    if (ctx._chart) { ctx._chart.destroy(); }
    ctx._chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($grafik->pluck('bulan')),
            datasets: [{
                label: 'BB (kg)',
                data: @json($grafik->pluck('bb')),
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124,58,237,0.08)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#7c3aed',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) { return ctx.parsed.y + ' kg'; }
                    }
                }
            },
            scales: {
                y: { min: 0, grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 }, color: '#9ca3af' } },
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#9ca3af' } }
            }
        }
    });
}
</script>
@endif
