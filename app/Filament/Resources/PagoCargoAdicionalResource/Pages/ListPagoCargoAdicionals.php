<?php

namespace App\Filament\Resources\PagoCargoAdicionalResource\Pages;

use App\Filament\Resources\PagoCargoAdicionalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPagoCargoAdicionals extends ListRecords
{
    protected static string $resource = PagoCargoAdicionalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
