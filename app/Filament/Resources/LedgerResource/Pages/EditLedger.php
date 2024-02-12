<?php

namespace App\Filament\Resources\LedgerResource\Pages;

use App\Filament\Resources\LedgerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Request;

class EditLedger extends EditRecord
{
    protected static string $resource = LedgerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\CreateAction::make()
            ->color("primary")
            ->label("Print")
            ->url(function($record){
              $url =  Request::url();
                $urlArray = explode("/", $url);
                return  route('pdf', $urlArray[5]) ;
            }),
            Actions\CreateAction::make()
            ->color("success")
            ->label("Create New")
            ->url(env('APP_URL').'admin/ledgers/create'),

        ];
    }

}
