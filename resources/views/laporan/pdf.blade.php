<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; }
        h2 { font-size: 14px; margin-bottom: 4px; }
        p { margin: 2px 0; color: #666; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #059669; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #f0f0f0; }
        tr:nth-child(even) { background: #f8fafb; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 8px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan {{ ucfirst(str_replace('_', ' ', $jenis)) }}</h2>
        <p>Bulan: {{ $bulan }} / Tahun: {{ $tahun }}</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                @if(in_array($jenis, ['timbang', 'status_gizi']))
                    <th>No</th><th>Posyandu</th><th>Nama Anak</th>
                    <th>Tgl Periksa</th><th>Berat</th><th>Tinggi</th>
                    <th>Status BB/U</th><th>Status TB/U</th>
                @elseif($jenis === 'imunisasi')
                    <th>No</th><th>Nama Anak</th><th>Jenis Imunisasi</th>
                    <th>Tgl Imunisasi</th><th>Kader</th>
                @elseif($jenis === 'vitamin_a')
                    <th>No</th><th>Posyandu</th><th>Nama Anak</th>
                    <th>Dosis</th><th>Tgl Distribusi</th>
                @elseif($jenis === 'pmt')
                    <th>No</th><th>Posyandu</th><th>Jenis PMT</th>
                    <th>Penerima</th><th>Jumlah</th><th>Tgl</th>
                @elseif($jenis === 'lansia')
                    <th>No</th><th>Posyandu</th><th>Nama Lansia</th>
                    <th>Tgl Periksa</th><th>Berat</th><th>Tinggi</th>
                    <th>IMT</th><th>Tensi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            <tr>
                @if(in_array($jenis, ['timbang', 'status_gizi']))
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->posyandu?->nama ?? '-' }}</td>
                    <td>{{ $row->anak?->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_periksa)->format('d/m/Y') }}</td>
                    <td>{{ $row->berat_kg }} kg</td>
                    <td>{{ $row->tinggi_cm }} cm</td>
                    <td>{{ $row->hasilGizi?->status_bbU ?? '-' }}</td>
                    <td>{{ $row->hasilGizi?->status_tbU ?? '-' }}</td>
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
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
