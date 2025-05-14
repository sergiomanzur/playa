<?php

namespace App\Filament\Resources\CargoAdicionalResource\Pages;

use App\Filament\Resources\CargoAdicionalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCargoAdicional extends EditRecord
{
    protected static string $resource = CargoAdicionalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
