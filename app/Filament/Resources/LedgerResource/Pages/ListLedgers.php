<?php

namespace App\Filament\Resources\LedgerResource\Pages;

use App\Filament\Resources\LedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListLedgers extends ListRecords
{
    protected static string $resource = LedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
            ->color("success")
            ->exports([
                ExcelExport::make()
                    ->fromTable()
                    ->withFilename(fn ($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                    ->withWriterType(\Maatwebsite\Excel\Excel::CSV)
                    ->withColumns([
                        Column::make('updated_at'),
                    ])
            ]),
            // Actions\CreateAction::make()
            // ->color("success")
            // ->label("Export Data")
            // ->url(function(){

            //     // env('APP_URL').'admin/ledgers/create'.

            //     // dd();
            //     if (count(\Request::query()) == 0) {
            //         return route('exportInvoice', 0);
            //     } else {
            //         $tableFilters = \Request::query('tableFilters');
            //         if ($tableFilters && array_key_exists('invoice_date', $tableFilters)) {
            //             $array = strval(json_encode($tableFilters['invoice_date']));
            //             return route('exportInvoice', 0);
            //             // return route('exportInvoice', ['id' => $array]);
            //         } else {
            //             // Handle case where 'tableFilters' or 'invoice_date' key is missing
            //             return route('exportInvoice', 0);
            //         }
            //     }
            // }),
        ];
    }
}
