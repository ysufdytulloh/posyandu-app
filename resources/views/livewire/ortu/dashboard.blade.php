<div style="max-width:480px; margin:0 auto; min-height:100vh; background:#f8f7ff; font-family:'Inter',sans-serif;">

    {{-- FIXED HEADER --}}
    <div style="position:fixed; top:0; left:50%; transform:translateX(-50%); width:100%; max-width:480px; z-index:100;">
        <div style="background:#5b21b6; padding:18px 18px 24px; border-radius:0 0 28px 28px; position:relative; overflow:hidden;">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>
            <div style="position:absolute; bottom:-40px; left:-10px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.04);"></div>

            {{-- Nama & Posyandu --}}
            <div style="display:flex; justify-content:space-between; align-items:center; position:relative; margin-bottom:14px;">
                <div>
                    <p style="font-size:11px; color:rgba(255,255,255,0.6); margin:0;">Selamat datang 👋</p>
                    <p style="font-size:18px; font-weight:700; color:#fff; margin:4px 0 0;">{{ auth()->user()->name }}</p>
                    <div style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                        <div style="width:6px; height:6px; border-radius:50%; background:#a3e635;"></div>
                        <p style="font-size:11px; color:rgba(255,255,255,0.65); margin:0;">
                            @forelse($data as $item){{ $item['posyandu']?->nama ?? '-' }}@break @empty - @endforelse
                            • {{ $data->count() }} anak
                        </p>
                    </div>
                </div>
                <div style="width:42px; height:42px; border-radius:13px; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700; color:#fff; border:1.5px solid rgba(255,255,255,0.2);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>

           {{-- FLOATING JADWAL --}}
            @if($jadwalBerikutnya)
            @php
                $sisaHari = (int) \Carbon\Carbon::now()->diffInDays($jadwalBerikutnya->tgl_jadwal, false);
            @endphp
            <div style="background:#fff; border-radius:16px; padding:12px 14px; box-shadow:0 8px 24px rgba(91,33,182,0.2); position:relative; z-index:10;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,#7c3aed,#5b21b6); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:10px; color:#9ca3af; margin:0;">Jadwal posyandu berikutnya</p>
                        <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:2px 0 0;">
                            {{ $jadwalBerikutnya->tgl_jadwal->translatedFormat('d F Y') }}
                        </p>
                        <p style="font-size:11px; color:#7c3aed; margin:1px 0 0; font-weight:500;">
                            {{ $jadwalBerikutnya->posyandu->nama }} • {{ \Carbon\Carbon::parse($jadwalBerikutnya->jam_mulai)->format('H.i') }} WIB
                        </p>
                    </div>
                    <div style="background:#ede9fe; border-radius:10px; padding:6px 10px; text-align:center; flex-shrink:0;">
                        <p style="font-size:10px; color:#7c3aed; margin:0; font-weight:500;">Sisa</p>
                        <p style="font-size:16px; font-weight:700; color:#5b21b6; margin:0; line-height:1.1;">{{ $sisaHari }}</p>
                        <p style="font-size:9px; color:#8b5cf6; margin:0;">hari</p>
                    </div>
                </div>
            </div>
            @else
            <div style="background:rgba(255,255,255,0.12); border-radius:16px; padding:12px 14px; border:1px solid rgba(255,255,255,0.15);">
                <p style="font-size:12px; color:rgba(255,255,255,0.7); margin:0; text-align:center;">Belum ada jadwal posyandu berikutnya</p>
            </div>
            @endif

        </div>
    </div>

    {{-- CONTENT --}}
    <div style="padding:210px 14px 110px;">

        {{-- ACCORDION ANAK --}}
        @forelse($data as $item)
        @php
            $anak      = $item['anak'];
            $timbang   = $item['timbang_terakhir'];
            $imunisasi = $item['imunisasi'];
            $isOpen    = in_array($anak->id, $expanded);
            $statusBB  = $timbang?->hasilGizi?->status_bbU ?? null;
            $statusTB  = $timbang?->hasilGizi?->status_tbU ?? null;
        @endphp

        <div style="background:#fff; border-radius:16px; margin-bottom:10px; border:{{ $isOpen ? '1.5px solid #7c3aed' : '0.5px solid #ede9fe' }}; box-shadow:0 {{ $isOpen ? '4px 16px rgba(124,58,237,0.12)' : '2px 8px rgba(91,33,182,0.06)' }}; overflow:hidden;">

            <div wire:click="toggle({{ $anak->id }})"
                 style="padding:14px 16px; cursor:pointer; display:flex; align-items:center; gap:12px; user-select:none;">
                <div style="width:42px; height:42px; border-radius:12px; background:{{ $isOpen ? 'linear-gradient(135deg,#7c3aed,#5b21b6)' : 'linear-gradient(135deg,#ede9fe,#ddd6fe)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <span style="font-size:14px; font-weight:700; color:{{ $isOpen ? '#fff' : '#7c3aed' }};">{{ strtoupper(substr($anak->nama, 0, 2)) }}</span>
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:14px; font-weight:700; color:#1e1b4b; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $anak->nama }}</p>
                    <p style="font-size:11px; color:#8b5cf6; margin:3px 0 0;">
                        {{ $anak->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        @if($anak->tgl_lahir) &nbsp;•&nbsp; {{ $anak->tgl_lahir->diffInMonths(now()) }} bulan @endif
                    </p>
                </div>
                @if($statusBB)
                <span style="font-size:11px; padding:3px 8px; border-radius:20px; font-weight:600; flex-shrink:0;
                    background:{{ $statusBB === 'Normal' ? '#eaf3de' : '#fee2e2' }};
                    color:{{ $statusBB === 'Normal' ? '#3b6d11' : '#991b1b' }};">{{ $statusBB }}</span>
                @else
                <span style="font-size:11px; padding:3px 8px; border-radius:20px; font-weight:500; flex-shrink:0; background:#f3f4f6; color:#9ca3af;">Belum ditimbang</span>
                @endif
                <div style="width:26px; height:26px; border-radius:8px; background:#f5f3ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; transform:rotate({{ $isOpen ? '90deg' : '0deg' }});">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            </div>

            @if($isOpen)
            <div style="padding:0 16px 14px; border-top:1px solid #f3f4f6;">

                {{-- BB & TB --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin:12px 0;">
                    <div style="background:#f8f7ff; border-radius:12px; padding:12px; border:1px solid #ede9fe;">
                        <p style="font-size:10px; color:#8b5cf6; margin:0 0 4px; font-weight:500;">Berat badan</p>
                        @if($timbang)
                            <p style="font-size:20px; font-weight:700; color:#1e1b4b; margin:0;">{{ $timbang->berat_kg }}<span style="font-size:11px; color:#8b5cf6;"> kg</span></p>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0 0;">{{ $timbang->tgl_periksa->format('d M Y') }}</p>
                        @else
                            <p style="font-size:18px; font-weight:700; color:#d1d5db; margin:0;">—</p>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0 0;">Belum ditimbang</p>
                        @endif
                    </div>
                    <div style="background:#f8f7ff; border-radius:12px; padding:12px; border:1px solid #ede9fe;">
                        <p style="font-size:10px; color:#8b5cf6; margin:0 0 4px; font-weight:500;">Tinggi badan</p>
                        @if($timbang)
                            <p style="font-size:20px; font-weight:700; color:#1e1b4b; margin:0;">{{ $timbang->tinggi_cm }}<span style="font-size:11px; color:#8b5cf6;"> cm</span></p>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0 0;">TB/U: {{ $statusTB ?? '-' }}</p>
                        @else
                            <p style="font-size:18px; font-weight:700; color:#d1d5db; margin:0;">—</p>
                            <p style="font-size:10px; color:#9ca3af; margin:2px 0 0;">Belum diukur</p>
                        @endif
                    </div>
                </div>

                {{-- IMUNISASI TERAKHIR --}}
                @if($imunisasi->isNotEmpty())
                <div style="margin-bottom:12px;">
                    <p style="font-size:11px; font-weight:600; color:#6b7280; margin:0 0 8px;">Imunisasi terakhir</p>
                    @foreach($imunisasi as $im)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 10px; background:#f8f7ff; border-radius:10px; border:1px solid #ede9fe; margin-bottom:6px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:28px; height:28px; border-radius:8px; background:#eaf3de; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#3b6d11" stroke-width="2" stroke-linecap="round"><path d="M18 2l4 4-10 10H8v-4L18 2z"/></svg>
                            </div>
                            <div>
                                <p style="font-size:12px; color:#1e1b4b; margin:0; font-weight:600;">{{ $im->jenisImunisasi?->nama ?? '-' }}</p>
                                <p style="font-size:10px; color:#9ca3af; margin:1px 0 0;">{{ $im->tgl_imunisasi->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span style="font-size:10px; background:#eaf3de; color:#3b6d11; padding:2px 8px; border-radius:20px; font-weight:600; flex-shrink:0;">Selesai</span>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- LIHAT DETAIL --}}
                <a href="{{ route('ortu.detail', $anak->id) }}"
                   style="display:flex; align-items:center; justify-content:center; gap:6px; width:100%; padding:11px; border-radius:12px; background:linear-gradient(135deg,#7c3aed,#5b21b6); color:#fff; font-size:13px; font-weight:600; text-decoration:none; box-sizing:border-box;">
                    Lihat Detail Lengkap
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
            @endif
        </div>
        @empty
        <div style="background:#fff; border-radius:20px; padding:40px 20px; text-align:center;">
            <div style="width:56px; height:56px; border-radius:16px; background:#f5f3ff; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="1.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <p style="font-size:15px; font-weight:700; color:#1e1b4b; margin:0;">Belum ada data anak</p>
            <p style="font-size:12px; color:#9ca3af; margin:8px 0 0; line-height:1.5;">Hubungi kader posyandu untuk menghubungkan akun Anda</p>
        </div>
        @endforelse

    </div>

    {{-- FIXED FOOTER --}}

    <div style="position:fixed; bottom:0; left:50%; transform:translateX(-50%); width:100%; max-width:480px; background:#f8f7ff; padding:12px 14px 16px; z-index:100;">
        {{-- TOMBOL KEHAMILAN --}}
        @if(auth()->user()->ibu_id)
        <a href="{{ route('ortu.kehamilan') }}"
        style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:12px; border-radius:14px; background:linear-gradient(135deg,#7c3aed,#5b21b6); color:#fff; font-size:13px; font-weight:600; text-decoration:none; box-sizing:border-box; margin-bottom:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            Data Kehamilan Saya
        </a>
        @endif
        <button wire:click="logout"
                style="width:100%; padding:13px; border-radius:14px; border:1.5px solid #ddd6fe; background:#fff; color:#7c3aed; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Keluar
        </button>
        <p style="text-align:center; font-size:11px; color:#c4b5fd; margin:8px 0 0;">
            &copy; {{ date('Y') }} Sistem Informasi Posyandu
        </p>
    </div>

</div>
