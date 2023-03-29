<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BalancesResource\Pages;
use App\Filament\Resources\BalancesResource\RelationManagers;
use App\Models\Balances;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BalancesResource extends Resource
{
    protected static ?string $model = Balances::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('lote_id')
                    ->relationship('lote', 'nombre')
                    ->required(),
                Toggle::make('tiene_deuda'),
                Forms\Components\TextInput::make('total')
                    ->numeric()
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                        ->numeric()
                        ->decimalPlaces(2) // Set the number of digits after the decimal point.
                        ->decimalSeparator('.') // Add a separator for decimal numbers.
                        ->mapToDecimalSeparator(['.']) // Map additional characters to the decimal separator.
                        ->minValue(1) // Set the minimum value that the number can be.
                        ->normalizeZeros() // Append or remove zeros at the end of the number.
                        ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                        ->thousandsSeparator(',') // Add a separator for thousands.
                    ),
                Forms\Components\TextInput::make('credito')
                    ->numeric()
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                        ->numeric()
                        ->decimalPlaces(2) // Set the number of digits after the decimal point.
                        ->decimalSeparator('.') // Add a separator for decimal numbers.
                        ->mapToDecimalSeparator(['.']) // Map additional characters to the decimal separator.
                        ->minValue(1) // Set the minimum value that the number can be.
                        ->normalizeZeros() // Append or remove zeros at the end of the number.
                        ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                        ->thousandsSeparator(',') // Add a separator for thousands.
                    ),
                Select::make('plan_de_pagos')
                    ->options([
                        '1' => 'De Contado',
                        '12' => '12 pagos',
                        '18' => '18 pagos',
                        '24' => '24 pagos',
                        'libre' => 'Libre'
                    ]),
                Forms\Components\Select::make('interes_id')
                    ->relationship('interes', 'interes')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('lote.nombre'),
                Tables\Columns\TextColumn::make('tiene_deuda'),
                Tables\Columns\TextColumn::make('total'),
                Tables\Columns\TextColumn::make('credito'),
                Tables\Columns\TextColumn::make('plan_de_pagos'),
                Tables\Columns\TextColumn::make('interes.interes'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBalances::route('/'),
            'create' => Pages\CreateBalances::route('/create'),
            'edit' => Pages\EditBalances::route('/{record}/edit'),
        ];
    }
}
