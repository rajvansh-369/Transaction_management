<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LedgerResource\Pages;
use App\Filament\Resources\LedgerResource\RelationManagers;
use App\Filament\Resources\LedgerResource\RelationManagers\CustomerProductRelationManager;
use App\Models\Customer;
use App\Models\Ledger;
use App\Models\Product;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class LedgerResource extends Resource
{
    protected static ?string $model = Ledger::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('customer_id')
                    ->options(Customer::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live(),

                Forms\Components\TextInput::make('bill_no')
                    ->default(function () {

                        $ledger = Ledger::orderBy('created_at', 'desc')->first();
                        return $ledger ? $ledger->bill_no + 1 : '#0001';
                    })
                    ->required()
                    ->maxLength(255),
                // Section::make('Add Product')
                //     ->description('Prevent abuse by limiting the number of requests per period')
                //     ->schema([
                //         Repeater::make('product')
                //         // ->relationship('products')
                //             ->schema([
                //                 Select::make('product_id')
                //                     ->options(Product::all()->pluck('name', 'id'))
                //                     ->searchable()

                //                     ->required(),
                //                 Select::make('product_qty')
                //                     ->options(function () {

                //                         $qty = [];
                //                         for ($i = 0; $i <= 10; $i++) {

                //                             $qty[$i] = $i;
                //                         }
                //                         return $qty;
                //                     })
                //                     ->required(),
                //             ])
                //             ->columns(2),

                //     ]),
                TextInput::make('labour')
                ->afterStateUpdated(function (Set $set,?Ledger $record , $state, Get $get) {

                    $totalPrice = $record->total_price->sum(function ($product) {
                        return $product->price * $product->pivot->product_qty;
                    });

                    if($get('bardana') != '' || $get('total_due') != ''){
                    $set('total_amount',(float)$totalPrice+ $state*15+ $get('bardana')*15);

                    $set('total_due', $get('total_amount') - $get('total_due'));
                    }else{
                        $set('total_amount',(float)$totalPrice+ $state*15);
                    }
                })
                    // ->required()
                    ->numeric()
                    ->live(onBlur: true),
                TextInput::make('bardana')
                ->afterStateUpdated(function (Set $set,?Ledger $record , $state, Get $get) {

                    $totalPrice = $record->total_price->sum(function ($product) {
                        return $product->price * $product->pivot->product_qty;
                    });

                    // dd($get('total_credit'));
                    if($get('labour') != '' || $get('total_due') != ''){

                        $set('total_amount',(float)$totalPrice+ $state*20 + $get('labour')*15);


                        $set('total_due', $get('total_amount') - $get('total_due'));
                    }else{
                        $set('total_amount',(float)$totalPrice+ $state*15);
                    }

                }) ->live(onBlur: true)
                    // ->required()
                        ,
                TextInput::make('total_amount')
                    // ->required()
                    ->readonly()
                    ,
                TextInput::make('total_credit')
                ->afterStateUpdated(function (Set $set, $state, Get $get) {


                    // dd($get('total_amount') ,$state);
                   $set('total_due', $get('total_amount') - $state);

                })

                // ->required()
                ->numeric()
                ->live(onBlur: true),
                TextInput::make('total_due')
                    // ->required()
                ->readonly()
                   ,

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bill_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_ids')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_credit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_due')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('labour')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bardana')
                    ->numeric()
                    ->sortable(),
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
            CustomerProductRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLedgers::route('/'),
            'create' => Pages\CreateLedger::route('/create'),
            'edit' => Pages\EditLedger::route('/{record}/edit'),
        ];
    }
}
