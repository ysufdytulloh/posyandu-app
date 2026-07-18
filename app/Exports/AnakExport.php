<?php

namespace App\Exports;

use App\Models\Anak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnakExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Anak::with(['ibu', 'posyandu'])->orderBy('nama')->get();
    }

    public function title(): string
    {
        return 'Data Anak';
    }

    public function headings(): array
    {
        return [
            'No', 'Nama', 'JK', 'Tgl Lahir', 'Anak Ke',
            'Nama Ibu', 'Posyandu',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $row->nama,
            $row->jk === 'L' ? 'Laki-laki' : 'Perempuan',
            $row->tgl_lahir?->format('d/m/Y') ?? '-',
            $row->anak_ke ?? '-',
            $row->ibu?->nama ?? '-',
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
