<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'icomoon-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('price')
                //     ->required()
                //     ->maxLength(255),
                Fieldset::make('Weight Management')
                    ->schema([
                        Forms\Components\TextInput::make('nug')
                            ->required()
                            ->label("Weight per NUG (KG)")
                            ->maxLength(255),

                        // Forms\Components\TextInput::make('petiKG')
                        //     ->required()
                        //     ->afterStateUpdated(function (Set $set,  $state, Get $get) {

                        //         // dd($get('petiG'),(($state ?? 0) /1000));
                        //         $set('peti',  ($state + (($get('petiG') ?? 0) /1000)));
                        //     })
                        //     ->live()
                        //     ->label("Peti Weight in KG")
                        //     ->maxLength(255),

                        // Forms\Components\TextInput::make('petiG')
                        //     ->required()
                        //     ->afterStateUpdated(function (Set $set,  $state, Get $get) {

                        //         // dd($get('petiKG '), (($state ?? 0)/1000) );
                        //         $set('peti',  $get('petiKG') + (($state ?? 0)/1000));
                        //     })
                        //     ->live()
                        //     ->label("Peti Weight in Grams")
                        //     ->maxLength(255),
                        // Forms\Components\TextInput::make('peti')
                        //     ->required()
                        //     ->readonly()
                        //     ->label('Total Peti Weight per NUG (KG)')
                        //     ->maxLength(255),
                        // Forms\Components\TextInput::make('net_weight')
                        //     ->required()
                        //     ->maxLength(255),
                        // Forms\Components\TextInput::make('gross_weight')
                        //     ->required()
                        //     ->maxLength(255),

                    ])->columns(4)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nug')
                    ->label('Kg/NUG')
                    ->searchable(),
                Tables\Columns\TextColumn::make('peti')
                    ->label('Peti Weight (/Kg)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
