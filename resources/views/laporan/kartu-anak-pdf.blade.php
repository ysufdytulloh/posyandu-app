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
        <strong>Kartu Kesehatan Anak</strong>
        <p>Rekap Seluruh Data Kesehatan Anak</p>
    </div>

    <table>
        <tr>
            <th>Nama Anak</th> <td>{{  $anak['nama'] ?? '-' }}</td>
            <th>Jenis Kelamin</th> <td>{{ $anak['jk'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tanggal Lahir</th> <td>{{  $anak['tgl_lahir'] ?? '-' }}</td>
            <th>Nama Ibu</th> <td>{{ $anak['ibu'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Posyandu</th> <td>{{ $anak['posyandu'] ?? '-' }}</td>
            <th>Kader</th> <td>{{ $kader?->name ?? '-' }}</th>
        </tr>
    </table>

    {{-- TIMBANG --}}
    @if($timbang->isNotEmpty())
    <div class="section-title">Riwayat Timbang</div>
    <table>
        <thead><tr>
            <th>No</th><th>Tgl Periksa</th><th>Berat (kg)</th><th>Tinggi (cm)</th>
            <th>LK (cm)</th><th>LILA (cm)</th>
            <th>Status BB/U</th><th>Z-Score BB/U</th>
            <th>Status TB/U</th><th>Z-Score TB/U</th>
            <th>Status BB/TB</th>
        </tr></thead>
        <tbody>
            @foreach($timbang as $i => $t)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $t->tgl_periksa->format('d/m/Y') }}</td>
                <td>{{ $t->berat_kg }}</td>
                <td>{{ $t->tinggi_cm }}</td>
                <td>{{ $t->lingkar_kepala_cm ?? '-' }}</td>
                <td>{{ $t->lila_cm ?? '-' }}</td>
                <td>{{ $t->hasilGizi?->status_bbU ?? '-' }}</td>
                <td>{{ $t->hasilGizi?->bbU_zscore ? number_format($t->hasilGizi->bbU_zscore, 2) : '-' }}</td>
                <td>{{ $t->hasilGizi?->status_tbU ?? '-' }}</td>
                <td>{{ $t->hasilGizi?->tbU_zscore ? number_format($t->hasilGizi->tbU_zscore, 2) : '-' }}</td>
                <td>{{ $t->hasilGizi?->status_bbTb ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- IMUNISASI --}}
    @if($imunisasi->isNotEmpty())
    <div class="section-title">Riwayat Imunisasi</div>
    <table>
        <thead><tr>
            <th>No</th><th>Jenis Imunisasi</th><th>Tgl Imunisasi</th><th>Kader</th>
        </tr></thead>
        <tbody>
            @foreach($imunisasi as $i => $im)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $im->jenisImunisasi?->nama ?? '-' }}</td>
                <td>{{ $im->tgl_imunisasi->format('d/m/Y') }}</td>
                <td>{{ $im->kader?->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- SDIDTK --}}
    @if($sdidtk->isNotEmpty())
    <div class="section-title">Riwayat SDIDTK</div>
    <table>
        <thead><tr>
            <th>No</th><th>Tgl Periksa</th><th>Usia (bln)</th><th>MK</th><th>MH</th><th>BB</th><th>SK</th><th>Hasil</th>
        </tr></thead>
        <tbody>
            @foreach($sdidtk as $i => $s)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $s->tgl_periksa->format('d/m/Y') }}</td>
                <td>{{ $s->usia_bulan }}</td>
                <td>{{ $s->motorik_kasar }}</td>
                <td>{{ $s->motorik_halus }}</td>
                <td>{{ $s->bicara_bahasa }}</td>
                <td>{{ $s->sosial_kemandirian }}</td>
                <td>{{ $s->hasil }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- VITAMIN A --}}
    @if($vitamin_a->isNotEmpty())
    <div class="section-title">Riwayat Vitamin A</div>
    <table>
        <thead><tr>
            <th>No</th><th>Tgl Distribusi</th><th>Dosis</th><th>Kader</th>
        </tr></thead>
        <tbody>
            @foreach($vitamin_a as $i => $v)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $v->tgl_distribusi->format('d/m/Y') }}</td>
                <td>{{ $v->dosis }}</td>
                <td>{{ $v->kader?->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- OBAT CACING --}}
    @if($obat_cacing->isNotEmpty())
    <div class="section-title">Riwayat Obat Cacing</div>
    <table>
        <thead><tr>
            <th>No</th><th>Tgl Pemberian</th><th>Dosis</th><th>Keterangan</th>
        </tr></thead>
        <tbody>
            @foreach($obat_cacing as $i => $o)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $o->tgl_pemberian->format('d/m/Y') }}</td>
                <td>{{ $o->dosis }}</td>
                <td>{{ $o->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

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
