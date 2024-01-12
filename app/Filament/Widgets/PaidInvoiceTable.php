<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LedgerResource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PaidInvoiceTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(LedgerResource::getEloquentQuery()->where('is_paid',0))
            ->defaultPaginationPageOption(option : 5)
            ->defaultSort(column : "created_at" ,direction:"asc")
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bill_no')
                    ->searchable(),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('icomoon-cross'),



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
            ]);
    }
}
