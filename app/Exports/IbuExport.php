<?php

namespace App\Exports;

use App\Models\Ibu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IbuExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Ibu::with('posyandu')->orderBy('nama')->get();
    }

    public function title(): string
    {
        return 'Data Ibu';
    }

    public function headings(): array
    {
        return [
            'No', 'NIK', 'Nama', 'Tgl Lahir', 'Alamat',
            'No HP', 'Gol. Darah', 'Posyandu',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $row->nik ?? '-',
            $row->nama,
            $row->tgl_lahir?->format('d/m/Y') ?? '-',
            $row->alamat ?? '-',
            $row->no_hp ?? '-',
            $row->goldar ?? '-',
            $row->posyandu?->nama ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF059669']]],
        ];
    }
}
