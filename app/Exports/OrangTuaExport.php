<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrangTuaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return User::with(['ibu.posyandu'])
            ->where('role', 'orang_tua')
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Nama Ibu',
            'Posyandu',
            'Tanggal Daftar',
        ];
    }

    public function map($user): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $user->name,
            $user->email,
            $user->ibu?->nama ?? '-',
            $user->ibu?->posyandu?->nama ?? '-',
            $user->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
