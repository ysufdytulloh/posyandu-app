<div style="max-width:480px; margin:0 auto; min-height:100vh; background:#f8f7ff; font-family:'Inter',sans-serif;">

    {{-- FIXED HEADER --}}
    <div style="position:fixed; top:0; left:50%; transform:translateX(-50%); width:100%; max-width:480px; z-index:100;">
        <div style="background:#5b21b6; padding:16px 16px 20px; border-radius:0 0 28px 28px; position:relative; overflow:hidden;">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>
            <div style="display:flex; align-items:center; gap:12px; position:relative;">
                <a href="{{ route('ortu.dashboard') }}"
                   style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; text-decoration:none; flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
                <div style="flex:1;">
                    <p style="font-size:11px; color:rgba(255,255,255,0.6); margin:0;">Portal Orang Tua</p>
                    <p style="font-size:17px; font-weight:700; color:#fff; margin:3px 0 0;">Data Kehamilan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div style="padding:100px 14px 90px;">

        @forelse($kehamilan as $k)
        @php
            $periksaTerakhir = $k->periksaKehamilan->first();
            $totalPeriksa    = $k->periksaKehamilan->count();
            $k1 = $k->periksaKehamilan->where('kunjungan_ke', 'K1')->first();
            $k2 = $k->periksaKehamilan->where('kunjungan_ke', 'K2')->first();
            $k3 = $k->periksaKehamilan->where('kunjungan_ke', 'K3')->first();
            $k4 = $k->periksaKehamilan->where('kunjungan_ke', 'K4')->first();
        @endphp

        {{-- CARD KEHAMILAN --}}
        <div style="background:#fff; border-radius:16px; padding:14px 16px; margin-bottom:14px; border:1px solid #ede9fe; box-shadow:0 4px 16px rgba(91,33,182,0.1);">

            {{-- Header kehamilan --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
                <div>
                    <p style="font-size:12px; color:#9ca3af; margin:0;">HPHT</p>
                    <p style="font-size:15px; font-weight:700; color:#1e1b4b; margin:3px 0 0;">{{ $k->hpht?->format('d M Y') ?? '-' }}</p>
                </div>
                <span style="font-size:12px; padding:4px 12px; border-radius:20px; font-weight:600;
                    background:{{ $k->status === 'aktif' ? '#ede9fe' : ($k->status === 'melahirkan' ? '#eaf3de' : '#fee2e2') }};
                    color:{{ $k->status === 'aktif' ? '#5b21b6' : ($k->status === 'melahirkan' ? '#3b6d11' : '#991b1b') }};">
                    {{ ucfirst($k->status) }}
                </span>
            </div>

            {{-- Info HPL & Usia --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:14px;">
                <div style="background:#f8f7ff; border-radius:12px; padding:12px; border:1px solid #ede9fe;">
                    <p style="font-size:10px; color:#8b5cf6; margin:0 0 3px; font-weight:500;">HPL</p>
                    <p style="font-size:13px; font-weight:700; color:#1e1b4b; margin:0;">{{ $k->tgl_perkiraan_lahir?->format('d M Y') ?? '-' }}</p>
                </div>
                <div style="background:#f8f7ff; border-radius:12px; padding:12px; border:1px solid #ede9fe;">
                    <p style="font-size:10px; color:#8b5cf6; margin:0 0 3px; font-weight:500;">Usia Kehamilan</p>
                    <p style="font-size:13px; font-weight:700; color:#1e1b4b; margin:0;">{{ $k->usia_kehamilan ?? '-' }} minggu</p>
                </div>
            </div>

            {{-- Progress Kunjungan K1-K4 --}}
            <div style="margin-bottom:14px;">
                <p style="font-size:12px; font-weight:600; color:#6b7280; margin:0 0 10px;">Progress Kunjungan ANC</p>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:6px;">
                    @foreach(['K1'=>$k1, 'K2'=>$k2, 'K3'=>$k3, 'K4'=>$k4] as $label => $kunjungan)
                    <div style="text-align:center; padding:10px 6px; border-radius:12px; border:1.5px solid {{ $kunjungan ? '#7c3aed' : '#e5e7eb' }}; background:{{ $kunjungan ? '#ede9fe' : '#f9fafb' }};">
                        <p style="font-size:14px; font-weight:700; color:{{ $kunjungan ? '#5b21b6' : '#d1d5db' }}; margin:0;">{{ $label }}</p>
                        <p style="font-size:10px; color:{{ $kunjungan ? '#7c3aed' : '#9ca3af' }}; margin:3px 0 0;">
                            {{ $kunjungan ? '✓ ' . $kunjungan->tgl_periksa->format('d/m') : '—' }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Pemeriksaan Terakhir --}}
            @if($periksaTerakhir)
            <div style="background:#f8f7ff; border-radius:12px; padding:12px 14px; border:1px solid #ede9fe;">
                <p style="font-size:12px; font-weight:600; color:#6b7280; margin:0 0 10px;">
                    Pemeriksaan Terakhir — {{ $periksaTerakhir->kunjungan_ke }} ({{ $periksaTerakhir->tgl_periksa->format('d M Y') }})
                </p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    @if($periksaTerakhir->berat_badan)
                    <div>
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Berat Badan</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:2px 0 0;">{{ $periksaTerakhir->berat_badan }} kg</p>
                    </div>
                    @endif
                    @if($periksaTerakhir->lila_cm)
                    <div>
                        <p style="font-size:10px; color:#9ca3af; margin:0;">LILA</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:2px 0 0;">{{ $periksaTerakhir->lila_cm }} cm</p>
                    </div>
                    @endif
                    @if($periksaTerakhir->tensi_sistol)
                    <div>
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Tensi</p>
                        <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:2px 0 0;">{{ $periksaTerakhir->tensi_sistol }}/{{ $periksaTerakhir->tensi_diastol }} mmHg</p>
                    </div>
                    @endif
                    @if($periksaTerakhir->status_gizi)
                    <div>
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Status Gizi</p>
                        <span style="font-size:11px; padding:2px 8px; border-radius:20px; font-weight:600; display:inline-block; margin-top:2px;
                            background:{{ $periksaTerakhir->status_gizi === 'Normal' ? '#eaf3de' : '#fee2e2' }};
                            color:{{ $periksaTerakhir->status_gizi === 'Normal' ? '#3b6d11' : '#991b1b' }};">
                            {{ $periksaTerakhir->status_gizi }}
                        </span>
                    </div>
                    @endif
                </div>
                @if($periksaTerakhir->catatan)
                <p style="font-size:11px; color:#6b7280; margin:10px 0 0; padding-top:8px; border-top:1px solid #e5e7eb;">
                    📝 {{ $periksaTerakhir->catatan }}
                </p>
                @endif
            </div>
            @endif

        </div>
        @empty
        <div style="background:#fff; border-radius:20px; padding:40px 20px; text-align:center;">
            <div style="width:56px; height:56px; border-radius:16px; background:#f5f3ff; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="1.5" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#1e1b4b; margin:0;">Belum ada data kehamilan</p>
            <p style="font-size:12px; color:#9ca3af; margin:8px 0 0; line-height:1.5;">Hubungi kader posyandu untuk input data kehamilan Anda</p>
        </div>
        @endforelse

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
