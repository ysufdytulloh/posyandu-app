<?php

namespace App\Filament\Resources\IbuResource\Pages;

use App\Exports\IbuExport;
use App\Filament\Resources\IbuResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListIbus extends ListRecords
{
    protected static string $resource = IbuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(function () {
                    return Excel::download(new IbuExport, 'data-ibu-' . now()->format('d-m-Y') . '.xlsx');
                }),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $data = \App\Models\Ibu::with('posyandu')->orderBy('nama')->get();
                    $pdf = Pdf::loadView('laporan.master-data-pdf', [
                        'data'      => $data,
                        'jenis'     => 'ibu',
                        'judul'     => 'Data Ibu',
                        'posyandu'  => null,
                        'kecamatan' => \App\Models\Posyandu::first()?->kecamatan ?? 'Semua',
                        'waktuCetak' => \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i'),
                    ])->setPaper('a4', 'portrait');
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'data-ibu-' . now()->format('d-m-Y') . '.pdf'
                    );
                }),
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
