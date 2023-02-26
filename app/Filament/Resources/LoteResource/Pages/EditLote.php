<?php

namespace App\Filament\Resources\LoteResource\Pages;

use App\Filament\Resources\LoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLote extends EditRecord
{
    protected static string $resource = LoteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
