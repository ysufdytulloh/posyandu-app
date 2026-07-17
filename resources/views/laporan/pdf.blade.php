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
        .info-box p { font-size: 11px; margin: 0; }

        /* ── TABEL ── */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #e5e7eb; color: #000; padding: 8px; border: 1px solid #000; font-size: 11px; text-transform: uppercase; }
        td { padding: 6px; border: 1px solid #000; font-size: 11px; text-align: center; }

        /* ── STATUS TEKS POLOS (Tanpa Border) ── */
        .status-text { font-size: 10px; font-weight: normal; }
        .z-warning { color: #dc2626; font-weight: bold; }

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
        @php
            $judulLaporan = match($jenis) {
                'timbang'      => 'Laporan Data Timbang Balita & Status Gizi',
                'imunisasi'    => 'Laporan Data Imunisasi',
                'vitamin_a'    => 'Laporan Distribusi Vitamin A',
                'pmt'          => 'Laporan Distribusi PMT',
                'lansia'       => 'Laporan Pemeriksaan Lansia',
                'rekapitulasi' => 'Rekapitulasi Data Seluruh Posyandu',
                default        => 'Laporan Data Posyandu',
            };
            $namaBulan = [
                '1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April',
                '5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus',
                '9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
            ];
        @endphp
        <strong>{{ $judulLaporan }}</strong>
        <p>Periode: {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }} &nbsp;&bull;&nbsp; Total Data: {{ count($data) }}</p>
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                @if($jenis === 'timbang')
                    <th>No</th><th>Posyandu</th><th>Nama Anak</th><th>Tgl Periksa</th>
                    <th>Berat (kg)</th><th>Tinggi (cm)</th>
                    <th>Status BB/U</th><th>Z-Score BB/U</th>
                    <th>Status TB/U</th><th>Z-Score TB/U</th>
                @elseif($jenis === 'imunisasi')
                    <th>No</th><th>Nama Anak</th><th>Jenis Imunisasi</th>
                    <th>Tgl Imunisasi</th><th>Kader</th>
                @elseif($jenis === 'vitamin_a')
                    <th>No</th><th>Posyandu</th><th>Nama Anak</th>
                    <th>Dosis</th><th>Tgl Distribusi</th>
                @elseif($jenis === 'pmt')
                    <th>No</th><th>Posyandu</th><th>Jenis PMT</th>
                    <th>Jenis Penerima</th><th>Nama Penerima</th>
                    <th>Jumlah</th><th>Tgl</th>
                @elseif($jenis === 'lansia')
                    <th>No</th><th>Posyandu</th><th>Nama Lansia</th>
                    <th>Tgl Periksa</th><th>Berat</th><th>Tinggi</th>
                    <th>IMT</th><th>Tensi</th>
                @elseif($jenis === 'rekapitulasi')
                    <th>No</th><th>Nama Posyandu</th><th>Total Balita</th>
                    <th>Total Lansia</th><th>Sudah Timbang</th>
                    <th>Stunting</th><th>Gizi Kurang</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            <tr>
                @if($jenis === 'timbang')
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->posyandu?->nama ?? '-' }}</td>
                    <td>{{ $row->anak?->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_periksa)->format('d/m/Y') }}</td>
                    <td>{{ $row->berat_kg }}</td>
                    <td>{{ $row->tinggi_cm }}</td>
                    <td class="status-text">{{ $row->hasilGizi?->status_bbU ?? '-' }}</td>
                    <td class="status-text{{ ($row->hasilGizi?->bbU_zscore < -2) ? 'z-warning' : '' }}">
                        {{ $row->hasilGizi?->bbU_zscore ? number_format($row->hasilGizi->bbU_zscore, 2) : '-' }}
                    </td>
                    <td class="status-text">{{ $row->hasilGizi?->status_tbU ?? '-' }}</td>
                    <td class="status-text {{ ($row->hasilGizi?->tbU_zscore < -2) ? 'z-warning' : '' }}">
                        {{ $row->hasilGizi?->tbU_zscore ? number_format($row->hasilGizi->tbU_zscore, 2) : '-' }}
                    </td>
                @elseif($jenis === 'imunisasi')
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->anak?->nama ?? '-' }}</td>
                    <td>{{ $row->jenisImunisasi?->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_imunisasi)->format('d/m/Y') }}</td>
                    <td>{{ $row->kader?->name ?? '-' }}</td>
                @elseif($jenis === 'vitamin_a')
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->posyandu?->nama ?? '-' }}</td>
                    <td>{{ $row->anak?->nama ?? '-' }}</td>
                    <td>{{ $row->dosis }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_distribusi)->format('d/m/Y') }}</td>
               @elseif($jenis === 'pmt')
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->posyandu?->nama ?? '-' }}</td>
                    <td>{{ $row->jenisPmt?->nama ?? '-' }}</td>
                    <td>{{ class_basename($row->penerima_type) }}</td>
                    <td
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
                    <td>{{ $row->jumlah }} {{ $row->satuan }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_distribusi)->format('d/m/Y') }}</td>
                @elseif($jenis === 'lansia')
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->posyandu?->nama ?? '-' }}</td>
                    <td>{{ $row->lansia?->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_periksa)->format('d/m/Y') }}</td>
                    <td>{{ $row->berat_kg }} kg</td>
                    <td>{{ $row->tinggi_cm }} cm</td>
                    <td>{{ $row->imt }}</td>
                    <td>{{ $row->tensi_sistol }}/{{ $row->tensi_diastol }}</td>
                @elseif($jenis === 'rekapitulasi')
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->anak_count }}</td>
                    <td>{{ $row->lansia_count }}</td>
                    <td>{{ $row->total_timbang }}</td>
                    <td>{{ $row->total_stunting }}</td>
                    <td>{{ $row->total_gizi_kurang }}</td>
                @endif
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
