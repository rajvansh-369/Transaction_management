<?php

namespace App\Filament\Resources\LedgerResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;

class CustomerProductRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([


               TextInput::make('name')
                    ->required()
                    ->maxLength(255),
               TextInput::make('product_qty')
                    ->required()
                    ->maxLength(255),
               TextInput::make('product_price')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('product_qty'),
                TextColumn::make('price')
                ->getStateUsing(function ($record) {
                    return $record->product_price * $record->product_qty ;
                })
                // ->getStateUsing(function($state,Closure $get)
                //     {

                //         return $state;


                // })
                ,


            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                   TextInput::make('product_qty')->required(),
                   TextInput::make('product_price')->required()
                   ->numeric()
                   ->label('Rate'),
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([

                Tables\Actions\AttachAction::make()
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                   TextInput::make('product_qty')->required()
                  ,
                ]),
            ]);
    }
}
