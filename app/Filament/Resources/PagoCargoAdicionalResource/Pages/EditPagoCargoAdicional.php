<?php

namespace App\Filament\Resources\PagoCargoAdicionalResource\Pages;

use App\Filament\Resources\PagoCargoAdicionalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPagoCargoAdicional extends EditRecord
{
    protected static string $resource = PagoCargoAdicionalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
