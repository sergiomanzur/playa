<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagosResource\Pages;
use App\Filament\Resources\PagosResource\RelationManagers;
use App\Models\Pagos;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagosResource extends Resource
{
    protected static ?string $model = Pagos::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lote_id')
                    ->relationship('lote', 'nombre')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('cantidad')->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                    ->numeric()
                    ->decimalPlaces(2) // Set the number of digits after the decimal point.
                    ->decimalSeparator('.') // Add a separator for decimal numbers.
                    ->mapToDecimalSeparator(['.']) // Map additional characters to the decimal separator.
                    ->minValue(1) // Set the minimum value that the number can be.
                    ->normalizeZeros() // Append or remove zeros at the end of the number.
                    ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                    ->thousandsSeparator(',') // Add a separator for thousands.
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lote.nombre'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('cantidad'),
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
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePagos::route('/create'),
            'edit' => Pages\EditPagos::route('/{record}/edit'),
        ];
    }
}
