<?php

namespace App\Filament\Resources\LedgerResource\Pages;

use App\Filament\Resources\LedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLedgers extends ListRecords
{
    protected static string $resource = LedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\CreateAction::make()
            ->color("success")
            ->label("Create New")
            ->url(function(){

                // env('APP_URL').'admin/ledgers/create'.

                // dd();
                if (count(\Request::query()) == 0) {
                    return route('exportInvoice', 0);
                } else {
                    $tableFilters = \Request::query('tableFilters');
                    if ($tableFilters && array_key_exists('invoice_date', $tableFilters)) {
                        $array = strval(json_encode($tableFilters['invoice_date']));
                        return route('exportInvoice', 0);
                        // return route('exportInvoice', ['id' => $array]);
                    } else {
                        // Handle case where 'tableFilters' or 'invoice_date' key is missing
                        return route('exportInvoice', 0);
                    }
                }
            }),
        ];
    }
}
