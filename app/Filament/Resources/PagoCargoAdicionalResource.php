<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagoCargoAdicionalResource\Pages;
use App\Filament\Resources\PagoCargoAdicionalResource\RelationManagers;
use App\Models\PagoCargoAdicional;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagoCargoAdicionalResource extends Resource
{
    protected static ?string $model = PagoCargoAdicional::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar'; // Changed icon
    protected static ?string $navigationGroup = 'Cargos Adicionales';
    protected static ?string $pluralModelLabel = 'pagos cargos adicionales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cargo_adicional_id')
                    ->relationship('cargoAdicional', 'descripcion')
                    ->required(),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('$.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cargoAdicional.descripcion'),
                Tables\Columns\TextColumn::make('total')->money('usd', true),
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
            'index' => Pages\ListPagoCargoAdicionals::route('/'),
            'create' => Pages\CreatePagoCargoAdicional::route('/create'),
            'edit' => Pages\EditPagoCargoAdicional::route('/{record}/edit'),
        ];
    }
}
