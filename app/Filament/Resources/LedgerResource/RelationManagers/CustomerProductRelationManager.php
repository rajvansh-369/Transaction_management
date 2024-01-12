<?php

namespace App\Filament\Resources\LedgerResource\RelationManagers;

use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
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
    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([


               TextInput::make('name')
                    ->required()
                    ->maxLength(255),
               TextInput::make('product_qty')
                     ->label("Nug")
                     ->hidden()
                    ->maxLength(255),
               TextInput::make('product_price')
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('product_qty')
                ->label("Nug")
                 ->getStateUsing(function ($record, ?Product $product) {

                    // dd($product);
                    // if($record->product_qty != 0 ){

                    //     return $record->product_qty ;
                    // }else{

                        return $product->nug ;

                    // }
                }),
                TextColumn::make('net_weight')
                ->label('Net Weight')
                ->getStateUsing(function ($record, ?Product $product) {

                    return $record->net_weight;
                    // dd($product);

                }),
                TextColumn::make('gross_weight')
                ->label('Gross Weight')
                ->getStateUsing(function ($record, ?Product $product) {

                    return $record->gross_weight;
                    // dd($product);

                }),
                TextColumn::make('price')
                ->getStateUsing(function ($record, ?Product $product) {

                    // dd($product);
                    if( $record->product_price != 0  ){

                        return $record->product_price ;
                    }else{

                        return $product->price ;

                    }
                }),
                TextColumn::make('rate')
                ->label('Total Price')
                ->getStateUsing(function ($record, ?Product $product) {

                    // dd($product);
                    if( $record->product_price != 0  ){

                        return $record->product_price * $product->nug ;
                    }else{

                        return $product->price * $product->nug ;

                    }
                }),



            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                ->preloadRecordSelect()
                ->recordSelect(
                    fn (Select $select) => $select->placeholder('Select a Product'),
                )
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect(),
                   TextInput::make('product_qty')->label("Nug")->hidden(),
                   TextInput::make('product_price')
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
