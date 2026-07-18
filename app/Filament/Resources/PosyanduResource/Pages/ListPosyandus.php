<?php

namespace App\Filament\Resources\PosyanduResource\Pages;

use App\Exports\PosyanduExport;
use App\Filament\Resources\PosyanduResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPosyandus extends ListRecords
{
    protected static string $resource = PosyanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(fn () => Excel::download(
                    new PosyanduExport,
                    'data-posyandu-' . now()->format('d-m-Y') . '.xlsx'
                )),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $data = \App\Models\Posyandu::withCount(['anak', 'lansia'])
                        ->orderBy('nama')->get();
                    $pdf = Pdf::loadView('laporan.master-data-pdf', [
                        'data'      => $data,
                        'jenis'     => 'posyandu',
                        'judul'     => 'Data Posyandu',
                        'posyandu'  => null,
                        'kecamatan' => \App\Models\Posyandu::first()?->kecamatan ?? 'Semua',
                        'waktuCetak' => \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i'),
                    ])->setPaper('a4', 'portrait');
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'data-posyandu-' . now()->format('d-m-Y') . '.pdf'
                    );
                }),
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
