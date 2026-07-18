<?php

namespace App\Exports;

use App\Models\Lansia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LansiaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return Lansia::with('posyandu')->orderBy('nama')->get();
    }

    public function title(): string
    {
        return 'Data Lansia';
    }

    public function headings(): array
    {
        return [
            'No', 'NIK', 'Nama', 'JK', 'Tgl Lahir',
            'Alamat', 'No HP', 'Posyandu',
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
            $row->jk === 'L' ? 'Laki-laki' : 'Perempuan',
            $row->tgl_lahir?->format('d/m/Y') ?? '-',
            $row->alamat ?? '-',
            $row->no_hp ?? '-',
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
