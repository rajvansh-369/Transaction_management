<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LedgerResource;
use App\Models\Ledger;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DueInvoiceTable extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;


    public function table(Table $table): Table
    {
        return $table
            ->query(LedgerResource::getEloquentQuery()->where('is_paid', 0)->where('total_amount', "!=", ""))
            ->defaultPaginationPageOption(option: 5)
            ->defaultSort(column: "created_at", direction: "asc")
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bill_no')
                    ->searchable(),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueIcon('go-check-circle-16')
                    ->falseIcon('fwb-o-x-circle'),



                Tables\Columns\TextColumn::make('labour')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bardana')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_due')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_credit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->url(fn (Ledger $requset): string => url('admin/ledgers/'.$requset->id."/edit")),
                Action::make('feature')
                    ->label('Download PDF')
                    ->url(fn (Ledger $requset): string => route('pdf', ['id' => $requset->id]))
                    ->visible(fn (Ledger $request) => $request->total_amount !== null)
            ])
            ->bulkActions([

            ]);
    }
}
