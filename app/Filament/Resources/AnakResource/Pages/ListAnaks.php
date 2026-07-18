<?php

namespace App\Filament\Resources\AnakResource\Pages;

use App\Exports\AnakExport;
use App\Filament\Resources\AnakResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListAnaks extends ListRecords
{
    protected static string $resource = AnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(function () {
                    return Excel::download(new AnakExport, 'data-anak-' . now()->format('d-m-Y') . '.xlsx');
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $data = \App\Models\Anak::with(['ibu', 'posyandu'])->orderBy('nama')->get();
                    $pdf = Pdf::loadView('laporan.master-data-pdf', [
                        'data'      => $data,
                        'jenis'     => 'anak',
                        'judul'     => 'Data Anak',
                        'posyandu'  => null,
                        'kecamatan' => \App\Models\Posyandu::first()?->kecamatan ?? 'Semua',
                        'waktuCetak' => \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i'),
                    ])->setPaper('a4', 'portrait');
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'data-anak-' . now()->format('d-m-Y') . '.pdf'
                    );
                }),
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
