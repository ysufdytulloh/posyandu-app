<?php

namespace App\Exports;

use App\Models\Posyandu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PosyanduExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Posyandu::withCount(['anak', 'lansia'])
            ->orderBy('nama')->get();
    }

    public function title(): string
    {
        return 'Data Posyandu';
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Posyandu', 'Kelurahan', 'Kecamatan',
            'Alamat', 'RT', 'RW', 'Total Balita', 'Total Lansia', 'Status',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $row->nama,
            $row->kelurahan ?? '-',
            $row->kecamatan ?? '-',
            $row->alamat ?? '-',
            $row->rt ?? '-',
            $row->rw ?? '-',
            $row->anak_count,
            $row->lansia_count,
            $row->is_active ? 'Aktif' : 'Tidak Aktif',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF059669']]],
        ];
    }
}
