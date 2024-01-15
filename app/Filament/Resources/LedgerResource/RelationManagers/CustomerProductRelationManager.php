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

                Select::make('name')
                ->options(Product::all()->pluck('name', 'id'))->searchable(),
                TextInput::make('product_qty')->label("Nug"),
                   TextInput::make('product_price')
                   ->numeric()
                   ->label('Rate/Kg'),

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

                    // dd($record);
                    // if($record->product_qty != 0 ){

                    //     return $record->product_qty ;
                    // }else{

                        return $record->pivot->product_qty ;

                    // }
                }),
                TextColumn::make('net_weight')
                ->label('Net Weight (KG)')
                ->getStateUsing(function ($record, ?Product $product) {

                    return $record->pivot->product_qty * $product->nug;
                    // dd($product);

                }),
                TextColumn::make('gross_weight')
                ->label('Gross Weight (KG)')
                ->getStateUsing(function ($record, ?Product $product) {

                    return ($record->pivot->product_qty *  $product->nug ) +  ($product->peti * $record->pivot->product_qty) ;
                    // dd($product);

                }),
                TextColumn::make('price')
                ->label('Total Price')
                ->getStateUsing(function ($record, ?Product $product) {

                    // dd($record->product_price , $product->nug , $record->nug );


                        return $record->product_price * ($product->nug * $record->pivot->product_qty  );

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
                   TextInput::make('product_qty')->label("Nug"),
                   TextInput::make('product_price')
                   ->numeric()
                   ->label('Rate/Kg'),
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
                   TextInput::make('product_qty')->required(),
                   TextInput::make('product_price')->required()
                  ,
                ]),
            ]);
    }
}
