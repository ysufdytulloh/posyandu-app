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
        <strong>KARTU KESEHATAN LANSIA</strong>
        <p>Riwayat Pemeriksaan Kesehatan di Posyandu</p>
    </div>

    <table>
        <tr>
            <th>Nama Lansia</th> <td>{{ $lansia['nama'] ?? '-' }}</td>
            <th>Tanggal Lahir</th> <td>{{ $lansia['tgl_lahir'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th> <td>{{ $lansia['jk'] ?? '-' }}</td>
            <th>No. HP</th> <td>{{ $lansia['no_hp'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Posyandu</th> <td>{{ $lansia['posyandu'] ?? '-' }}</td>
            <th>Kader</th> <td>{{ $kader?->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alamat</th> <td colspan="3">{{ $lansia['alamat'] ?? '-' }}</td>
        </tr>
        @if(($lansia['riwayat_penyakit'] ?? '-') !== '-')
            <tr>
                <th>Riwayat Penyakit</th>
                <td colspan="3">{{ $lansia['riwayat_penyakit'] }}</td>
            </tr>
        @endif
    </table>


    {{-- RIWAYAT TIMBANG --}}
    <div class="section-title">Riwayat Pemeriksaan Kesehatan</div>

    <table>
       <thead>
            <tr>
                <th>No</th>
                <th>Tgl Periksa</th>
                <th>Berat (kg)</th>
                <th>Tinggi (cm)</th>
                <th>IMT</th>
                <th>Tensi</th>
                <th>Gula Darah</th>
                <th>Kolesterol</th>
                <th>Asam Urat</th>
                <th>Keluhan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayat as $i => $r)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $r->tgl_periksa->format('d/m/Y') }}</td>
                <td>{{ $r->berat_kg }}</td>
                <td>{{ $r->tinggi_cm }}</td>
                <td class="{{ $r->imt > 25 ? 'warning' : '' }}">{{ number_format($r->imt, 1) }}</td>
                <td class="{{ $r->tensi_sistol > 140 ? 'warning' : '' }}">
                    {{ $r->tensi_sistol && $r->tensi_diastol ? $r->tensi_sistol.'/'.$r->tensi_diastol : '-' }}
                </td>
                <td class="{{ $r->gula_darah > 200 ? 'warning' : '' }}">
                    {{ $r->gula_darah ? $r->gula_darah.' mg/dL' : '-' }}
                </td>
                <td class="{{ $r->kolesterol > 200 ? 'warning' : '' }}">
                    {{ $r->kolesterol ? $r->kolesterol.' mg/dL' : '-' }}
                </td>
                <td class="{{ $r->asam_urat > 7 ? 'warning' : '' }}">
                    {{ $r->asam_urat ? $r->asam_urat.' mg/dL' : '-' }}
                </td>
                <td font-size:9px;">{{ $r->keluhan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
