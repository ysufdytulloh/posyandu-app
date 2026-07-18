<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: 'Times New Roman', serif; font-size: 11px; color: #000; }

        /* ── KOP SURAT REVISI ── */
        .kop-surat { width: 100%; border-bottom: 4px double #000; padding-bottom: 5px; margin-bottom: 10px; text-align: center; }
        .kop-surat h1 { font-size: 18px; text-transform: uppercase; margin: 0; }
        .kop-surat h2 { font-size: 16px; text-transform: uppercase; margin: 2px 0; }
        .kop-surat p { font-size: 12px; font-weight: bold; margin: 1px 0; }

        /* ── INFO BOX ── */
        .info-box { background: #f0f0f0; padding: 10px; border: 1px solid #ccc; margin-bottom: 15px; text-align: center; }
        .info-box strong { font-size: 14px; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .info-box p { font-size: 12px; margin: 0; }

        .chart-container { border: 1px solid #d1d5db; padding: 15px; border-radius: 6px; text-align: center; margin-bottom: 15px;background-color: #fcfcfc; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #000; padding-bottom: 3px; margin: 12px 0 8px; }
        .legend-line { display: inline-block; width: 18px; height: 3px; vertical-align: middle; margin-right: 5px; }
        .legend-dot { display: inline-block; width: 9px; height: 9px; border-radius: 50%; vertical-align: middle; margin-right: 5px; }


        /* ── TABEL ── */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #e5e7eb; color: #000; padding: 8px; border: 1px solid #000; font-size: 11px; text-transform: uppercase; }
        td { padding: 6px; border: 1px solid #000; font-size: 11px; text-align: center; }


        /* ── STATUS TEKS POLOS (Tanpa Border) ── */
        .status-text { font-size: 10px; font-weight: normal; }
        .warning { color: #dc2626; font-weight: bold; }

        .ttd-container { position: fixed; bottom: 25mm; width: 100%;}
        .ttd-area { width: 100%; border: none; }
        .ttd-area td { width: 50%; vertical-align: top; text-align: center; border: none; font-size: 12px; font-family: 'Times New Roman', serif; line-height: 1.2;}
        .ttd-space { height: 60px; }
        .ttd-name { font-weight: bold; font-size: 12px; margin-top: 5px;}
        .ttd-area td { font-size: 12px; font-family: 'Times New Roman', serif; }

         .footer {
            position: fixed;
            bottom: 5mm;
            left: 10mm;
            right: 10mm;
            height: 20px;
            font-size: 8px;
            text-align: right;
            padding-top: 2px;
        }
    </style>
</head>
<body>

   {{-- KOP SURAT --}}
    <div class="kop-surat">
        <div class="sistem" style="text-transform: uppercase;">SISTEM INFORMASI POSYANDU</div>
        @if($posyandu)
            <h1 style="text-transform: uppercase;">{{ strtoupper($posyandu->nama) }}</h1>
            <div class="sub" style="text-transform: uppercase;">
                KEL. {{ strtoupper($posyandu->kelurahan) }} - KEC. {{ strtoupper($posyandu->kecamatan) }}
            </div>
            <div class="sub" style="text-transform: uppercase;">
                ALAMAT: {{ strtoupper($posyandu->alamat) }} - RT {{ $posyandu->rt }} / RW {{ $posyandu->rw }}
            </div>
        @else
            <h1 style="text-transform: uppercase;">POSYANDU {{ strtoupper($kecamatan) }}</h1>
            <div class="sub" style="text-transform: uppercase;">KEC. {{ strtoupper($kecamatan) }}</div>
        @endif
    </div>

    <!-- INFO BOX -->
    <div class="info-box">
        <strong>Kartu Ibu Hamil</strong>
        <p>Riwayat Kehamilan Ibu di Posyandu</p>
    </div>

    <table>
        <tr>
            <th>Nama Ibu</th> <td>{{  $ibu['nama'] ?? '-' }}</td>
            <th>Tanggal Lahir</th> <td>{{ $ibu['tgl_lahir'] ?? '-' }}</td>
            <th>Gol. Darah</th> <td>{{ $ibu['goldar'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>NIK</th> <td>{{ $ibu['nik'] }}</td>
            <th>No. HP</th> <td>{{ $ibu['no_hp'] ?? '-' }}</td>
            <th>Posyandu</th> <td>{{ $ibu['posyandu'] ?? '-' }}</td>
        </tr>

        <tr>
            <th>Alamat</th> <td colspan="5">{{ $ibu['alamat'] ?? '-' }}</td>

        </tr>
    </table>


    {{-- RIWAYAT TIMBANG --}}
    <div class="section-title">Riwayat Kehamilan</div>

    <table>
       <thead>
            <tr>
                <th>No</th>
                <th>HPHT</th>
                <th>HPL</th>
                <th>Usia Kehamilan</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kehamilan as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($k->hpht)->format('d/m/Y') }}</td>
                <td>{{ $k->tgl_perkiraan_lahir ? \Carbon\Carbon::parse($k->tgl_perkiraan_lahir)->format('d/m/Y') : '-' }}</td>
                <td>{{ $k->usia_kehamilan ? $k->usia_kehamilan . ' Minggu' : '-' }}</td>
                <td>{{ ucfirst($k->status ?? '-') }}</></td>
                <td>{{ $k->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PEMERIKSAAN PER KEHAMILAN --}}
    @foreach($kehamilan as $idx => $k)
    @if($k->periksaKehamilan->isNotEmpty())
    <div class="section-title">
        Pemeriksaan Kehamilan #{{ $idx + 1 }}
        — HPHT: {{ \Carbon\Carbon::parse($k->hpht)->format('d/m/Y') }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Periksa</th>
                <th>Kunjungan</th>
                <th>Usia (mgg)</th>
                <th>BB (kg)</th>
                <th>LILA (cm)</th>
                <th>Tensi</th>
                <th>Tab Fe</th>
                <th>Status Gizi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($k->periksaKehamilan->sortByDesc('tgl_periksa') as $i => $p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->tgl_periksa->format('d/m/Y') }}</td>
                <td>{{ $p->kunjungan_ke }}</td>
                <td>{{ $p->usia_kehamilan ?? '-' }}</td>
                <td>{{ $p->berat_badan ?? '-' }}</td>
                <td class="{{ $p->lila_cm && $p->lila_cm < 23.5 ? 'warning' : '' }}">
                    {{ $p->lila_cm ?? '-' }}
                </td>
                <td class="{{ $p->tensi_sistol > 140 ? 'warning' : '' }}">
                    {{ $p->tensi_sistol && $p->tensi_diastol ? $p->tensi_sistol.'/'.$p->tensi_diastol : '-' }}
                </td>
                <td>{{ $p->tablet_fe ? $p->tablet_fe.' tab' : '-' }}</td>
                <td class="{{ $p->status_gizi === 'KEK' ? 'warning' : '' }}">
                    {{ $p->status_gizi ?? '-' }}
                </td>
                <td style="text-align:left;font-size:9px;">{{ $p->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endforeach

    <!-- TTD -->
    <div class="ttd-container">
        <table class="ttd-area">
            <tr>
                <td>
                    Mengetahui,<br>Kepala Desa
                    <br><br><br><br><br>
                    <div class="ttd-name">( ........................... )</div>
                </td>
                <td>
                    {{ $posyandu->kelurahan ?? 'Posyandu' }}, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
                    Petugas Kesehatan
                    <br><br><br><br><br>
                    <div class="ttd-name">( {{ $kader?->name ?? '...........................' }} )</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak oleh Sistem Informasi Posyandu pada {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }} WIB
    </div>
</body>
</html>
