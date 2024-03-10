<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Customer;
use App\Models\Ledger;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
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

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        // function addressFieldset($id)
        // {

        //     $ledgers = Ledger::where('customer_id', $id)->get();
        //     // dd($ledger);

        //     $arr = [];
        //     $newArr = [];
        //     foreach ($ledgers as $ledger) {

        //         // dd($ledger->total_credit);
        //         $arr = array_merge($arr, [

        //             Fieldset::make('Price Calculation')
        //                 ->schema([
        //                     TextInput::make('total_credit' . $ledger->id)
        //                         ->default('adasd') // Set default data here
        //                         ->readonly()->label('Total Credit of ' . $ledger->bill_no),
        //                     TextInput::make('asdasdsad' . $ledger->id)->label('Casdasity'),
        //                     // ... other address fields
        //                 ]),

        //         ]);

        //         // array_merge($arr, $arr);
        //         //
        //     }
        //     // dd($arr, $ledgers);
        //     if (count($ledgers) == 0) {


        //         return [

        //             TextInput::make('address_line_2')->label('Address Line 2'),
        //             TextInput::make('city')->label('City'),
        //             TextInput::make('state')->label('State'),
        //             TextInput::make('zip')->label('Zip Code'),
        //             // ... other address fields

        //         ];
        //     }


        //     return $arr;





        //     return [

        //         TextInput::make('address_line_2')->label('Address Line 2'),
        //         TextInput::make('city')->label('City'),
        //         TextInput::make('state')->label('State'),
        //         TextInput::make('zip')->label('Zip Code'),
        //         // ... other address fields

        //     ];
        // }
        return $form
            ->schema([
                Select::make('customer_id')
                    ->label("Select Customer Name")
                    ->options(Customer::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    // ->afterStateUpdated(function (Set $set, ?Ledger $record, $state, Get $get) {


                    //     $set('selected_customer_id', $state);
                    //     // dd("dasd",$get('selected_customer_id'));
                    // })
                    ->live(),

                // Section::make('Address Details')
                //     ->schema(function (Get $get) {
                //         return addressFieldset($get('selected_customer_id'));
                //     })
                //     ->columnSpanFull()->live(),
                // Select::make('ledger_id')
                //     ->label("Select Customer Name")
                //     ->options(Ledger::all()->pluck('bill_no', 'id'))
                //     ->searchable()
                //     ->required()
                //     ->live(),
                Forms\Components\TextInput::make('credit')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('debit')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('debit')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
