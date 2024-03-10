<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\CreateAction::make()
            ->openUrlInNewTab()
            ->color("success")
            ->label("Adjust Leder")
            ->url(fn (): string => route('adjustInvoice'), shouldOpenInNewTab: true),
        ];
    }
}
