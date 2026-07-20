kms-pdf.blade.php

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
        @if(isset($posyandu) && $posyandu)
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
        <strong>KARTU MENUJU SEHAT (KMS)</strong>
        <p>Kurva Pertumbuhan Balita &mdash; Standar WHO / Kemenkes RI</p>
    </div>

    <table>
        <tr>
            <th>Nama Anak</th> <td>{{ $chart['anak']['nama'] ?? '-' }}</td>
            <th>Tanggal Lahir</th> <td>{{ $chart['anak']['lahir'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th> <td>{{ $chart['anak']['jk'] ?? '-' }}</td>
            <th>Nama Ibu</th> <td>{{ $chart['anak']['ibu'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Posyandu</th> <td>{{ $chart['posyandu']['nama'] ?? '-' }}</td>
            <th>Kader</th> <td>{{ $kader?->name ?? '-' }}</td>
        </tr>
    </table>

 <!-- LOGIKA KALKULASI GRAFIK SVG & GENERATE BASE64 -->
    @php
        $refUsia = $chart['refUsia'] ?? [];
        $sd3neg  = $chart['sd3neg'] ?? [];
        $sd2neg  = $chart['sd2neg'] ?? [];
        $median  = $chart['median'] ?? [];
        $sd2pos  = $chart['sd2pos'] ?? [];
        $sd3pos  = $chart['sd3pos'] ?? [];
        $points  = $chart['dataPoints'] ?? [];

        // LEBAR GRAFIK DISESUAIKAN (660px) KARENA MARGIN LEBIH KECIL (15mm)
        $svgW   = 660;
        $svgH   = 280;
        $padL   = 40;
        $padR   = 15;
        $padT   = 15;
        $padB   = 35;
        $chartW = $svgW - $padL - $padR;
        $chartH = $svgH - $padT - $padB;

        $base64Svg = '';

        if(count($refUsia) > 0) {
            $allY = array_merge($sd3neg, $sd3pos, array_column($points, 'y'));
            $minY = floor(min($allY)) - 1;
            $maxY = ceil(max($allY))  + 1;
            $minX = min($refUsia);
            $maxX = max($refUsia);

            $toX = fn($u) => $padL + (($u - $minX) / max(1, $maxX - $minX)) * $chartW;
            $toY = fn($v) => $padT + $chartH - (($v - $minY) / max(1, $maxY - $minY)) * $chartH;

            $buildLine = function($data) use ($refUsia, $toX, $toY) {
                $pts = [];
                foreach ($refUsia as $i => $u) {
                    if (isset($data[$i]) && $data[$i] !== null) {
                        $pts[] = round($toX($u), 1) . ',' . round($toY($data[$i]), 1);
                    }
                }
                return implode(' ', $pts);
            };

            // GENERATE STRING SVG
            $svgString = '<svg width="'.$svgW.'" height="'.$svgH.'" viewBox="0 0 '.$svgW.' '.$svgH.'" xmlns="http://www.w3.org/2000/svg">';
            $svgString .= '<rect x="'.$padL.'" y="'.$padT.'" width="'.$chartW.'" height="'.$chartH.'" fill="#ffffff" stroke="#d1d5db" stroke-width="1"/>';

            // Grid Horizontal
            for($y = $minY; $y <= $maxY; $y += 1) {
                $yp = round($toY($y), 1);
                $svgString .= '<line x1="'.$padL.'" y1="'.$yp.'" x2="'.($svgW - $padR).'" y2="'.$yp.'" stroke="#f3f4f6" stroke-width="1"/>';
                $svgString .= '<text x="'.($padL - 5).'" y="'.($yp + 3).'" text-anchor="end" font-size="8" fill="#6b7280">'.$y.'</text>';
            }

            // Grid Vertikal
            foreach($refUsia as $i => $usia) {
                if($i % 2 === 0) {
                    $xp = round($toX($usia), 1);
                    $svgString .= '<line x1="'.$xp.'" y1="'.$padT.'" x2="'.$xp.'" y2="'.($padT + $chartH).'" stroke="#f3f4f6" stroke-width="1"/>';
                    $svgString .= '<text x="'.$xp.'" y="'.($padT + $chartH + 15).'" text-anchor="middle" font-size="8" fill="#6b7280">'.$usia.'</text>';
                }
            }

            // Label Sumbu
            $svgString .= '<text x="'.($padL + ($chartW / 2)).'" y="'.($svgH - 2).'" text-anchor="middle" font-size="9" font-weight="bold" fill="#374151">USIA (BULAN)</text>';
            $svgString .= '<text x="12" y="'.($padT + ($chartH / 2)).'" text-anchor="middle" font-size="9" font-weight="bold" fill="#374151" transform="rotate(-90, 12, '.($padT + ($chartH / 2)).')">BERAT BADAN (KG)</text>';

            // Kurva Standar WHO
            $svgString .= '<polyline points="'.$buildLine($sd3neg).'" fill="none" stroke="#dc2626" stroke-width="1.2"/>';
            $svgString .= '<polyline points="'.$buildLine($sd2neg).'" fill="none" stroke="#d97706" stroke-width="1.2"/>';
            $svgString .= '<polyline points="'.$buildLine($median).'" fill="none" stroke="#059669" stroke-width="2"/>';
            $svgString .= '<polyline points="'.$buildLine($sd2pos).'" fill="none" stroke="#d97706" stroke-width="1.2"/>';
            $svgString .= '<polyline points="'.$buildLine($sd3pos).'" fill="none" stroke="#dc2626" stroke-width="1.2"/>';

            // Titik Anak & Garis Hubung
            $ptCoords = [];
            foreach($points as $pt) {
                $cx = round($toX($pt['x']), 1);
                $cy = round($toY($pt['y']), 1);
                $ptCoords[] = $cx.','.$cy;
            }

            if(count($ptCoords) > 1) {
                $svgString .= '<polyline points="'.implode(' ', $ptCoords).'" fill="none" stroke="#0f172a" stroke-width="1.8"/>';
            }

            // Render circle di atas garis agar rapi
            foreach($points as $pt) {
                $cx = round($toX($pt['x']), 1);
                $cy = round($toY($pt['y']), 1);
                $svgString .= '<circle cx="'.$cx.'" cy="'.$cy.'" r="4" fill="#0f172a" stroke="#ffffff" stroke-width="1.5"/>';
            }

            $svgString .= '</svg>';
            $base64Svg = 'data:image/svg+xml;base64,' . base64_encode($svgString);
        }
    @endphp

    <div class="section-title">Grafik Pertumbuhan Berat Badan Menurut Usia (BB/U)</div>

    @if(!empty($base64Svg))
    <div class="chart-container">
        <!-- GAMBAR GRAPHIC DIPERLEBAR SESUAI MARGIN 15mm -->
        <img src="{{ $base64Svg }}" width="660" height="280" style="display: block; margin: 0 auto;" alt="Grafik KMS">
    </div>
    @else
    <div class="chart-container" style="padding: 40px; color: #6b7280; font-style: italic;">
        <p>Data referensi grafik belum tersedia untuk kelompok usia ini.</p>
    </div>
    @endif

    <!-- LEGEND GRAFIK -->
    <table>
        <tr>
            <td>
                <span class="legend-line" style="background-color: #dc2626;"></span>
                -3 SD (Buruk)
            </td>
            <td>
                <span class="legend-line" style="background-color: #d97706;"></span>
                -2 SD (Kurang)
            </td>
            <td>
                <span class="legend-line" style="background-color: #059669; height: 4px;"></span>
                Median (Normal)
            </td>
            <td>
                <span class="legend-line" style="background-color: #d97706;"></span>
                +2 SD (Lebih)
            </td>
            <td>
                <span class="legend-line" style="background-color: #dc2626;"></span>
                +3 SD
            </td>
            <td>
                <span class="legend-dot" style="background-color: #0f172a;"></span>
                Data Aktual Anak
            </td>
        </tr>
    </table>

    {{-- RIWAYAT TIMBANG --}}
    <div class="section-title">Riwayat Penimbangan</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Periksa</th>
                <th>Usia (bln)</th>
                <th>Berat (kg)</th>
                <th>Tinggi (cm)</th>
                <th>Status BB/U</th>
                <th>Z-Score BB/U</th>
                <th>Status TB/U</th>
                <th>Z-Score TB/U</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timbangList as $i => $t)
            @php
                $bbU  = $t->hasilGizi?->status_bbU ?? '-';
                $tbU  = $t->hasilGizi?->status_tbU ?? '-';
                $bbZ  = $t->hasilGizi?->bbU_zscore;
                $tbZ  = $t->hasilGizi?->tbU_zscore;
                $usia = isset($chart['dataPoints'][$i]) ? $chart['dataPoints'][$i]['x'] : '-';
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->tgl_periksa->format('d/m/Y') }}</td>
                <td>{{ $usia }}</td>
                <td><strong>{{ $t->berat_kg }}</strong></td>
                <td>{{ $t->tinggi_cm }}</td>
                <td>{{ $bbU }}</td>
                <td class="{{ $bbZ !== null && $bbZ < -2 ? 'z-warning' : '' }}">
                    {{ $bbZ !== null ? number_format($bbZ, 2) : '-' }}
                </td>
                <td>{{ $tbU }}</td>
                <td class="{{ $tbZ !== null && $tbZ < -2 ? 'z-warning' : '' }}">
                    {{ $tbZ !== null ? number_format($tbZ, 2) : '-' }}
                </td>
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

