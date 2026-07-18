<?php

namespace App\Filament\Resources\LansiaResource\Pages;

use App\Exports\LansiaExport;
use App\Filament\Resources\LansiaResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListLansias extends ListRecords
{
    protected static string $resource = LansiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(function () {
                    return Excel::download(new LansiaExport, 'data-lansia-' . now()->format('d-m-Y') . '.xlsx');
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $data = \App\Models\Lansia::with('posyandu')->orderBy('nama')->get();
                    $pdf = Pdf::loadView('laporan.master-data-pdf', [
                        'data'      => $data,
                        'jenis'     => 'lansia',
                        'judul'     => 'Data Lansia',
                        'posyandu'  => null,
                        'kecamatan' => \App\Models\Posyandu::first()?->kecamatan ?? 'Semua',
                        'waktuCetak' => \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i'),
                    ])->setPaper('a4', 'portrait');
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'data-lansia-' . now()->format('d-m-Y') . '.pdf'
                    );
                }),
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
