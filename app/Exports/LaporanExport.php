<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Carbon;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(
        protected $data,
        protected ?string $jenis,
        protected ?string $bulan,
        protected ?string $tahun,
    ) {}

    public function collection()
    {
        return $this->data->map(function ($row, $i) {
            return match($this->jenis) {
                'timbang', 'status_gizi' => [
                    $i + 1,
                    $row->posyandu?->nama ?? '-',
                    $row->anak?->nama ?? '-',
                    Carbon::parse($row->tgl_periksa)->format('d/m/Y'),
                    $row->berat_kg,
                    $row->tinggi_cm,
                    $row->hasilGizi?->status_bbU ?? '-',
                    $row->hasilGizi?->bbU_zscore ?? '-',
                    $row->hasilGizi?->status_tbU ?? '-',
                    $row->hasilGizi?->tbU_zscore ?? '-',
                ],
                'imunisasi' => [
                    $i + 1,
                    $row->anak?->nama ?? '-',
                    $row->jenisImunisasi?->nama ?? '-',
                    Carbon::parse($row->tgl_imunisasi)->format('d/m/Y'),
                    $row->kader?->name ?? '-',
                ],
                'vitamin_a' => [
                    $i + 1,
                    $row->posyandu?->nama ?? '-',
                    $row->anak?->nama ?? '-',
                    $row->dosis,
                    Carbon::parse($row->tgl_distribusi)->format('d/m/Y'),
                ],
                'pmt' => [
                    $i + 1,
                    $row->posyandu?->nama ?? '-',
                    $row->jenisPmt?->nama ?? '-',
                    class_basename($row->penerima_type),
                    $row->jumlah . ' ' . $row->satuan,
                    Carbon::parse($row->tgl_distribusi)->format('d/m/Y'),
                ],
                'lansia' => [
                    $i + 1,
                    $row->posyandu?->nama ?? '-',
                    $row->lansia?->nama ?? '-',
                    Carbon::parse($row->tgl_periksa)->format('d/m/Y'),
                    $row->berat_kg,
                    $row->tinggi_cm,
                    $row->imt,
                    $row->tensi_sistol . '/' . $row->tensi_diastol,
                ],
                default => [],
            };
        });
    }

    public function headings(): array
    {
        return match($this->jenis) {
            'timbang', 'status_gizi' => ['No', 'Posyandu', 'Nama Anak', 'Tgl Periksa', 'Berat (kg)', 'Tinggi (cm)', 'Status BB/U', 'Z-Score BB/U', 'Status TB/U', 'Z-Score TB/U'],
            'imunisasi'              => ['No', 'Nama Anak', 'Jenis Imunisasi', 'Tgl Imunisasi', 'Kader'],
            'vitamin_a'              => ['No', 'Posyandu', 'Nama Anak', 'Dosis', 'Tgl Distribusi'],
            'pmt'                    => ['No', 'Posyandu', 'Jenis PMT', 'Penerima', 'Jumlah', 'Tgl Distribusi'],
            'lansia'                 => ['No', 'Posyandu', 'Nama Lansia', 'Tgl Periksa', 'Berat (kg)', 'Tinggi (cm)', 'IMT', 'Tensi'],
            default                  => [],
        };
    }

    public function title(): string
    {
        return 'Laporan ' . ucfirst($this->jenis);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
