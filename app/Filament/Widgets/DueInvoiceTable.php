<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LedgerResource;
use App\Models\Ledger;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

use Filament\Tables\Filters\Filter;

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
                    ->trueIcon('go-check-circle-16'),



                Tables\Columns\TextColumn::make('labour')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bardana')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_due')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_credit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (Ledger $requset): string => url('admin/ledgers/' . $requset->id . "/edit")),
                Action::make('feature')
                    ->label('Print Invoice')
                    ->url(fn (Ledger $requset): string => route('pdf', ['id' => $requset->id]))
                    ->visible(fn (Ledger $request) => $request->total_amount !== null)
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->bulkActions([]);
    }
}
