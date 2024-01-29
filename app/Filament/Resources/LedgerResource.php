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
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
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
                    ->label("Select Customer Name")
                    ->options(Customer::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live(),

                Forms\Components\TextInput::make('bill_no')
                    ->default(function () {

                        $ledger = Ledger::orderBy('created_at', 'desc')->first();
                        return $ledger ? $ledger->bill_no + 1 : '0001';
                    })
                    ->required()
                    ->readonly(),
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

                Fieldset::make('Price Calculation')
                    ->schema([
                        TextInput::make('labour')
                        ->label('Labour Charge(/Nug)')
                            ->required()

                            ->afterStateUpdated(function (Set $set, ?Ledger $record, $state, Get $get) {

                                $totalPrice = $record->total_price->sum(function ($product) {
                                    return $product->pivot->product_price  * $product->pivot->product_qty;
                                });

                                $totalNug = $record->products->sum(function ($product) {
                                    return  $product->pivot->product_qty/$product->nug;
                                });

                                // dd($totalPrice , $totalNug);
                                if ($get('bardana') != '' || $get('total_due') != '') {
                                    $set('total_amount', (float)$totalPrice + $state * $totalNug + (integer)$get('bardana') * $totalNug);

                                //   dd($get('total_amount') , $get('total_credit'));
                                    $set('total_due', (integer)$get('total_amount') - (integer)$get('total_credit'));
                                } else {
                                    $set('total_amount', (float)$totalPrice + $state * $totalNug);
                                }
                            })
                            // ->required()
                            ->numeric()
                            ->live(),
                        TextInput::make('bardana')
                        ->label('Bardana Charge(/Nug)')
                            ->required()

                            ->afterStateUpdated(function (Set $set, ?Ledger $record, $state, Get $get) {

                                $totalPrice = $record->total_price->sum(function ($product) {
                                    return $product->pivot->product_price  * $product->pivot->product_qty;
                                });

                                $totalNug = $record->products->sum(function ($product) {
                                    return  $product->pivot->product_qty/$product->nug;
                                });
                                // dd($get('total_credit'));
                                if ($get('labour') != '' || $get('total_due') != '') {

                                    $set('total_amount', (float)$totalPrice + $state * $totalNug + (integer)$get('labour') * $totalNug);


                                    $set('total_due', (integer)$get('total_amount') - (integer)$get('total_credit'));
                                } else {
                                    $set('total_amount', (float)$totalPrice + $state * $totalNug);
                                }
                            })->live()
                        // ->required()
                        ,
                        TextInput::make('total_amount')

                            // ->required()
                            ->readonly(),
                        TextInput::make('total_credit')

                            ->afterStateUpdated(function (Set $set, $state, Get $get) {


                                // dd($get('total_amount') ,$state);
                                $set('total_due', $get('total_amount') - $state);
                                if ($get('total_due') == 0) {


                                    $set('is_paid', 1);
                                } else {

                                    $set('is_paid', 0);
                                }
                            })

                            // ->required()
                            ->numeric()
                            ->live(onBlur:true),

                        TextInput::make('total_due')

                            // ->required()

                            ->readonly(),
                        Toggle::make('is_paid')
                            ->formatStateUsing(function (Set $set, $state, Get $get) {

                                if ($state == 1) {
                                    return True;
                                } else {

                                    return False;
                                }
                            })

                            ->label('Paid'),
                    ])->columns(3)->visibleOn('edit')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc','is_paid' , 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('Download PDF')
                    ->url(fn (Ledger $requset): string => route('pdf', ['id' => $requset->id]))
                    ->visible(fn (Ledger $request) => $request->total_amount !== null)
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
