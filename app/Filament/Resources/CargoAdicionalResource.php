<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CargoAdicionalResource\Pages;
use App\Filament\Resources\CargoAdicionalResource\RelationManagers;
use App\Models\CargoAdicional;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CargoAdicionalResource extends Resource
{
    protected static ?string $model = CargoAdicional::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Cargos Adicionales';
    protected static ?string $pluralModelLabel = 'cargos adicionales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descripcion')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('$'), // Added numeric and prefix for currency
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descripcion'),
                Tables\Columns\TextColumn::make('total')->money('mxn', true), // Formatted as currency
                Tables\Columns\TextColumn::make('user.name'),
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
            RelationManagers\PagosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCargoAdicionals::route('/'),
            'create' => Pages\CreateCargoAdicional::route('/create'),
            'edit' => Pages\EditCargoAdicional::route('/{record}/edit'),
        ];
    }
}
